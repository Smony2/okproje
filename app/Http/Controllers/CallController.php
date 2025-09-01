<?php

namespace App\Http\Controllers;

use App\Events\CallAccepted;
use App\Events\CallEnded;
use App\Events\CallInvited;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CallController extends Controller
{
    private function getAuthenticatedActor(Request $request): array
    {
        // Route prefix'e göre guard önceliği ver
        if ($request->is('katip/*') && auth('katip')->check()) {
            $katip = auth('katip')->user();
            return ['type' => 'Katip', 'id' => (int) $katip->id, 'identity' => 'Katip-' . $katip->id];
        }
        if ($request->is('avukat/*') && auth('avukat')->check()) {
            $avukat = auth('avukat')->user();
            return ['type' => 'Avukat', 'id' => (int) $avukat->id, 'identity' => 'Avukat-' . $avukat->id];
        }
        // Fallback: herhangi biri aktifse al
        if (auth('katip')->check()) {
            $katip = auth('katip')->user();
            return ['type' => 'Katip', 'id' => (int) $katip->id, 'identity' => 'Katip-' . $katip->id];
        }
        if (auth('avukat')->check()) {
            $avukat = auth('avukat')->user();
            return ['type' => 'Avukat', 'id' => (int) $avukat->id, 'identity' => 'Avukat-' . $avukat->id];
        }
        abort(401);
    }

    private function ensureParticipant(Conversation $conversation, array $actor): void
    {
        $isParticipant = ($actor['type'] === 'Avukat' && (int) $conversation->avukat_id === $actor['id'])
            || ($actor['type'] === 'Katip' && (int) $conversation->katip_id === $actor['id']);
        if (!$isParticipant) {
            Log::warning('CallController forbidden: user not participant', [
                'conversation_id' => (int) $conversation->id,
                'actor_type' => $actor['type'] ?? null,
                'actor_id' => $actor['id'] ?? null,
                'conv_avukat_id' => (int) $conversation->avukat_id,
                'conv_katip_id' => (int) $conversation->katip_id,
            ]);
            abort(403, 'Not a participant of this conversation');
        }
    }

    // POST: /{conversation}/call/invite
    public function invite(Request $request, Conversation $conversation)
    {
        $actor = $this->getAuthenticatedActor($request);
        $this->ensureParticipant($conversation, $actor);

        $validated = $request->validate([
            'room' => 'nullable|string|max:128',
        ]);

        $room = $validated['room'] ?? ('call-' . Str::uuid());

        // determine callee
        $toType = $actor['type'] === 'Avukat' ? 'Katip' : 'Avukat';
        $toId = $actor['type'] === 'Avukat' ? (int) $conversation->katip_id : (int) $conversation->avukat_id;

        broadcast(new CallInvited(
            conversationId: (int) $conversation->id,
            room: $room,
            fromType: $actor['type'],
            fromId: $actor['id'],
            toType: $toType,
            toId: $toId
        ))->toOthers();

        // Save call started message
        $this->saveCallMessage($conversation, $actor, $toType, $toId, 'call_started', $room);

        return response()->json(['ok' => true, 'room' => $room]);
    }

    // POST: /{conversation}/call/accept
    public function accept(Request $request, Conversation $conversation)
    {
        $actor = $this->getAuthenticatedActor($request);
        $this->ensureParticipant($conversation, $actor);

        $data = $request->validate([
            'room' => 'required|string|max:128',
        ]);

        // notify the other participant (original caller)
        $toType = $actor['type'] === 'Avukat' ? 'Katip' : 'Avukat';
        $toId = $actor['type'] === 'Avukat' ? (int) $conversation->katip_id : (int) $conversation->avukat_id;

        broadcast(new CallAccepted(
            conversationId: (int) $conversation->id,
            room: $data['room'],
            fromType: $actor['type'],
            fromId: $actor['id'],
            toType: $toType,
            toId: $toId
        ))->toOthers();

        // Update call message to answered
        $this->updateCallMessage($conversation, $data['room'], 'answered');

        return response()->json(['ok' => true]);
    }

    // POST: /{conversation}/call/token
    public function token(Request $request, Conversation $conversation)
    {
        $actor = $this->getAuthenticatedActor($request);
        $this->ensureParticipant($conversation, $actor);

        $data = $request->validate([
            'room' => 'required|string|max:128',
        ]);

        $tokenTtlMinutes = (int) Config::get('services.livekit.token_ttl_minutes', 60);
        $token = $this->generateLiveKitToken(
            identity: $actor['identity'],
            room: $data['room'],
            ttlMinutes: $tokenTtlMinutes
        );

        $wsUrl = Config::get('services.livekit.ws_url');

        $iceServers = [];
        $stun = Config::get('services.livekit.stun_url');
        if ($stun) {
            $iceServers[] = ['urls' => [$stun]];
        }
        $turnUrl = Config::get('services.livekit.turn_url');
        $turnUser = Config::get('services.livekit.turn_username');
        $turnPass = Config::get('services.livekit.turn_password');
        if ($turnUrl && $turnUser && $turnPass) {
            $iceServers[] = [
                'urls' => [$turnUrl],
                'username' => $turnUser,
                'credential' => $turnPass,
            ];
        }

        return response()->json([
            'ok' => true,
            'ws_url' => $wsUrl,
            'token' => $token,
            'iceServers' => $iceServers,
            'identity' => $actor['identity'],
        ]);
    }

    // POST: /{conversation}/call/end
    public function end(Request $request, Conversation $conversation)
    {
        $actor = $this->getAuthenticatedActor($request);
        $this->ensureParticipant($conversation, $actor);

        $room = (string) $request->input('room', '');

        $toType = $actor['type'] === 'Avukat' ? 'Katip' : 'Avukat';
        $toId = $actor['type'] === 'Avukat' ? (int) $conversation->katip_id : (int) $conversation->avukat_id;

        broadcast(new CallEnded(
            conversationId: (int) $conversation->id,
            room: $room,
            fromType: $actor['type'],
            fromId: $actor['id'],
            toType: $toType,
            toId: $toId
        ))->toOthers();

        // Update call message to ended
        $this->updateCallMessage($conversation, $room, 'ended');

        return response()->json(['ok' => true]);
    }

    private function generateLiveKitToken(string $identity, string $room, int $ttlMinutes = 60): string
    {
        $apiKey = (string) Config::get('services.livekit.api_key');
        $apiSecret = (string) Config::get('services.livekit.api_secret');

        $header = ['alg' => 'HS256', 'typ' => 'JWT'];
        $now = time();
        $exp = $now + ($ttlMinutes * 60);

        $payload = [
            'iss' => $apiKey,
            'sub' => $identity,
            'nbf' => $now - 10,
            'exp' => $exp,
            // LiveKit grants
            'video' => [
                'room' => $room,
                'roomJoin' => true,
                'canPublish' => true,
                'canSubscribe' => true,
            ],
        ];

        $segments = [
            $this->base64UrlEncode(json_encode($header, JSON_UNESCAPED_SLASHES)),
            $this->base64UrlEncode(json_encode($payload, JSON_UNESCAPED_SLASHES)),
        ];
        $signingInput = implode('.', $segments);
        $signature = hash_hmac('sha256', $signingInput, $apiSecret, true);
        $segments[] = $this->base64UrlEncode($signature);
        return implode('.', $segments);
    }

    private function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private function saveCallMessage(Conversation $conversation, array $actor, string $toType, int $toId, string $type, string $room): void
    {
        try {
            switch($type) {
                case 'call_started':
                    $messageText = '📞 Arama başlatıldı';
                    break;
                case 'call_ended':
                    $messageText = '📞 Arama sonlandırıldı';
                    break;
                case 'call_missed':
                    $messageText = '📞 Cevapsız arama';
                    break;
                default:
                    $messageText = '📞 Arama';
                    break;
            }

            Message::create([
                'conversation_id' => $conversation->id,
                'sender_type' => $actor['type'],
                'sender_id' => $actor['id'],
                'receiver_type' => $toType,
                'receiver_id' => $toId,
                'message' => $messageText,
                'type' => $type,
                'call_metadata' => [
                    'room' => $room,
                    'status' => 'initiated',
                    'caller_name' => $actor['type'] === 'Avukat' ? 'Avukat' : 'Katip',
                    'caller_id' => $actor['id'],
                    'callee_name' => $toType === 'Avukat' ? 'Avukat' : 'Katip',
                    'callee_id' => $toId,
                    'started_at' => now()->toISOString(),
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to save call message', [
                'error' => $e->getMessage(),
                'conversation_id' => $conversation->id,
                'type' => $type,
                'room' => $room
            ]);
        }
    }

    private function updateCallMessage(Conversation $conversation, string $room, string $status): void
    {
        try {
            $message = Message::where('conversation_id', $conversation->id)
                ->where('type', 'call_started')
                ->whereJsonContains('call_metadata->room', $room)
                ->latest()
                ->first();

            if ($message) {
                switch($status) {
                    case 'answered':
                        $messageText = '📞 Görüşme tamamlandı';
                        break;
                    case 'ended':
                        $messageText = '📞 Görüşme sonlandırıldı';
                        break;
                    case 'missed':
                        $messageText = '📞 Cevapsız arama';
                        break;
                    default:
                        $messageText = '📞 Arama';
                        break;
                }

                $metadata = $message->call_metadata ?? [];
                $metadata['status'] = $status;
                $metadata['ended_at'] = now()->toISOString();
                
                if ($status === 'ended' && isset($metadata['started_at'])) {
                    $startedAt = \Carbon\Carbon::parse($metadata['started_at']);
                    $endedAt = now();
                    $metadata['duration'] = $endedAt->diffInSeconds($startedAt);
                }

                $message->update([
                    'message' => $messageText,
                    'call_metadata' => $metadata
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to update call message', [
                'error' => $e->getMessage(),
                'conversation_id' => $conversation->id,
                'room' => $room,
                'status' => $status
            ]);
        }
    }
}


