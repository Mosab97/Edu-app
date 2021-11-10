<?php

namespace App\Http\Controllers\Api\v1\Chat;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\v1\Chat\ChatMessageResource;
use App\Http\Resources\Api\v1\General\FileResource;
use App\Models\ChatMessage;
use App\Models\Group;
use App\Models\GroupFile;
use App\Models\StudentGroups;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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
            'file' => 'sometimes|mimes:mp4,mp3,mov,ogg,qt,jpg,png,JPG,PNG,gif,pdf,word,xlsx,docx | max:20000',
        ]);
        $user = apiUser();
        $group = Group::query()->findOrFail($id);
        if ($user->user_type == User::user_type['TEACHER']) {
            if ($group->teacher_id != $user->id) return apiError(api('Not group teacher'));
        } else {
            if ($group->students()->where(['student_id' => $user->id])->count() == 0) return apiError(api('Not Group member'));
        }
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

    public function storeChatFile(Request $request, $group_id,$sender_id)
    {
        $img = $request->file_base64;
        $folderPath = "uploads/" . ChatMessage::manager_route . "/"; //path location

        $image_parts = explode(";base64,", $img);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $uniqid = uniqid();
        $file = $folderPath . $uniqid . '.' . $image_type;
        file_put_contents($file, $image_base64);


        $group = Group::findOrFail($group_id);
        $chatMessage = $group->chatMessages()->create([
            'sender_id' => $sender_id,
            'message' => $request->message,
            'type' => ChatMessage::type['file'],
        ]);
            $group->files()->create([
                'chat_message_id' => $chatMessage->id,
                'name' => 'null',
                'extension' => $image_type,
                'path' => $file,
            ]);
        Log::info('file_websocket', [
            'group' => $group_id,
            'file' => asset($file),
            'image_parts' => $image_parts,
            'image_type_aux' => $image_type_aux,
            'image_type' => $image_type,
        ]);
        return apiSuccess([
            // 'request' => $request->all(),
            'group' => $group_id,
            'path' => $file,
        ]);
        // dd($group_id, checkRequestIsWorkingOrNot());
    }


}
