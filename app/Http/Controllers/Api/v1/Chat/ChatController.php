<?php

namespace App\Http\Controllers\Api\v1\Chat;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\v1\Chat\ChatMessageResource;
use App\Http\Resources\Api\v1\General\FileResource;
use App\Models\ChatMessage;
use App\Models\Group;
use App\Models\GroupFile;
use App\Models\StudentGroups;
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
        $files = GroupFile::where(['group_id' => $group_id])->paginate($this->perPage);
        return apiSuccess([
            'items' => FileResource::collection($files->items()),
            'paginate' => paginate($files),
        ]);
    }

    public function delete_media(Request $request, $file_id)
    {
        $file = GroupFile::query()->findOrFail($file_id);
        $file->chatMessage()->delete();
        $file->delete();
        return apiSuccess(null, api('File Deleted Successfully'));
    }

    public function chatMessages(Request $request, $id)
    {
        $messages = ChatMessage::query()->with(['file'])->where('group_id', $id)->paginate($this->perPage);
//        dd(collect($messages->items())->pluck('file'));
        return apiSuccess([
            'items' => ChatMessageResource::collection($messages->items()),
            'paginate' => paginate($messages),
        ]);
    }


    public function sendMessage(Request $request, $id)
    {
        $request->validate([
            'message' => 'sometimes|min:1|max:200',
            'file' => 'sometimes|mimes:mp4,mov,ogg,qt,jpg,png,JPG,PNG,gif,pdf,word,xls,docx | max:20000',
        ]);
        $student = apiUser();
        $group = Group::query()->findOrFail($id);
        if ($group->students()->where(['student_id' => $student->id])->count() == 0) return apiError(api('Not Group member'));
        $chatMessage = $group->chatMessages()->create([
            'sender_id' => apiUser()->id,
            'message' => $request->message,
            'type' => $request->hasFile('file') ? ChatMessage::type['file'] : ChatMessage::type['text'],
        ]);
        if ($request->hasFile('file')) {
            $file = $this->uploadImage($request->file('file'), ChatMessage::manager_route, true);
            $group->files()->create([
                'chat_message_id' => $chatMessage->id,
                'name' => optional($file)['name'],
                'extension' => optional($file)['extension'],
                'path' => optional($file)['path'],
            ]);
        }

//        $user = apiUser();
//        Notification::send($user, new NewMessageNotification($group, $chatMessage));
//        app('firebase.firestore')->database()->collection('chat')->document($group->id)
//            ->set([
//                    'sender_id' => apiUser()->id,
//                    'message' => $request->message,
//                    'image' => $chatMessage->image,
//                    'timestamp' => Carbon::parse($request->updated_at)->format(DATE_FORMAT_FULL),
//                ]
//            );

        return apiSuccess(new ChatMessageResource($chatMessage), api('Message Send Successfully'));
    }

}
