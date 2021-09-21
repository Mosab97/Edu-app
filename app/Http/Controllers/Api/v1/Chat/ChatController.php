<?php

namespace App\Http\Controllers\Api\v1\Chat;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\v1\Chat\ChatMessageResource;
use App\Http\Resources\Api\v1\General\FileResource;
use App\Models\ChatMessage;
use App\Models\File;
use App\Models\Group;
use Illuminate\Http\Request;

class ChatController extends Controller
{

    public function getAllChats(Request $request)
    {
        $user = apiUser();
        $request['user_type'] = $user->user_type;
        return apiSuccess([
            'items' => [],// ChatMessageResource::collection($messages->items()),
//            'paginate' => paginate($chats),
        ]);
    }

    public function group_media(Request $request, $group_id)
    {
        $files = File::where(['target_id' => $group_id, 'target_type' => Group::class,])->paginate($this->perPage);
        return apiSuccess([
            'items' => FileResource::collection($files->items()),
            'paginate' => paginate($files),
        ]);
    }

    public function chatMessages(Request $request, $id)
    {
        $messages = ChatMessage::where('group_id', $id)->paginate($this->perPage);
        return apiSuccess([
            'items' => ChatMessageResource::collection($messages->items()),
            'paginate' => paginate($messages),
        ]);
    }


    public function sendMessage(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|min:1|max:200',
            'image' => 'sometimes|image',
        ]);
        $group = Group::findOrFail($id);
        $chatMessage = $group->chatMessages()->create([
            'sender_id' => apiUser()->id,
            'message' => $request->message,
        ]);

        $file = $this->uploadImage($request->file('image'), 'chat');
        if ($request->hasFile('image')) {
            $file = File::create([
                'target_id' => $group->id,
                'target_type' => Group::class,
                'path' => optional($file)['path'], 'name' => optional($file)['name'], 'extension' => optional($file)['extension'],
            ]);
        }

        $user = apiUser();
//        Notification::send($user, new NewMessageNotification($group, $chatMessage));
//        app('firebase.firestore')->database()->collection('chat')->document($group->id)
//            ->set([
//                    'sender_id' => apiUser()->id,
//                    'message' => $request->message,
//                    'image' => $chatMessage->image,
//                    'timestamp' => Carbon::parse($request->updated_at)->format(DATE_FORMAT_FULL),
//                ]
//            );

        return apiSuccess(null, api('Message Send Successfully'));
    }

}
