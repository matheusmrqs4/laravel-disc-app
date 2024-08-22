<?php

namespace App\Http\Controllers;

use App\Http\Requests\MessageRequest;
use App\Models\Channel;
use App\Models\Guild;
use App\Services\MessageService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class MessageController extends Controller
{
    public function __construct(
        private readonly MessageService $messageService
    ) {
    }

    /**
     * @param MessageRequest $request
     * @param Guild $guild
     * @param Channel $channel
     * @return JsonResponse
     */
    public function store(MessageRequest $request, Guild $guild, Channel $channel): JsonResponse
    {
        $data = $request->validated();

        $messageData = $this->messageService->sendMessage(
            $guild,
            $channel,
            $data
        );

        return response()
            ->json(
                $messageData,
                Response::HTTP_OK
            );
    }

    /**
     * @param Guild $guild
     * @param Channel $channel
     * @param int $messageId
     * @return JsonResponse
     * @throws \App\Exceptions\MessageException
     */
    public function destroy(Guild $guild, Channel $channel, int $messageId): JsonResponse
    {
        $this->messageService->deleteMessage($guild, $channel, $messageId);

        return response()
            ->json([
                'message' => 'Message deleted successfully'
            ], Response::HTTP_OK);
    }
}
