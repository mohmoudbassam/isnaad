<?php

namespace App\Http\Controllers\Client;

use App\Events\SendTicketMessage;
use App\Helpers\helper;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\CommentAttachments;
use App\Models\CommentReply;
use App\Models\Ticket;
use App\Models\TicketsFiles;
use App\Models\TicketStatus;
use App\order;
use App\user;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use Yajra\DataTables\DataTables;

class ClientTikect extends Controller
{
    use helper;

    public function index()
    {
        return view('m_design.Client.ticket.index');
    }

    public function create()
    {
        return view('m_design.Client.ticket.form');
    }

    public function check_order($order_number)
    {
        $store = auth()->user()->store;

        $order = order::query()->where('order_number', $order_number)
            ->where('store_id', $store->account_id)
            ->first();

        if ($order) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'this order not found'
            ]);
        }
    }

    public function save(Request $request)
    {

        $store = auth()->user()->store;

        if ($request->order_number) {
            $order = order::query()->where('order_number', $request->order_number)
                ->where('store_id', $store->account_id)
                ->first();
            if (!$order) {
                return redirect()->back()->with(['error' => 'this order not found']);
            }
        }

        $ticket = Ticket::query()->create([
            'title' => $request->title,
            'description' => $request->description,
            'ticket_number' => Str::uuid(),
            'status_id' => TicketStatus::query()->where('name', 'opened')->first()->id,
            'type_id' => $request->type,
            'order_number' => isset($order) ? $order->id : null,
            'store_id' => $store->account_id
        ]);

        if ($request->files && is_array($request->file('files'))) {
            foreach ($request->file('files') as $file) {
                $real_name = $file->getClientOriginalName();
                $store_Name = time() . rand(100, 999) . '.' . $file->getClientOriginalExtension();
                $file->move('ticket/', $store_Name);
                TicketsFiles::query()->create([
                    'ticket_id' => $ticket->id,
                    'real_name' => $real_name,
                    'path' => $store_Name
                ]);
            }

        }

        return redirect()->route('client-ticket')->with(['success' => 'Ticket was successfully created']);
    }

    public function list()
    {

        $ticket = Ticket::query()->with(['status', 'type'])
            ->where('store_id', auth()->user()->store->account_id);
        return DataTables::of($ticket)
            ->addColumn('actions', function ($item) {

                $confirm = '';

                $chat = '<a href="javascript:;" class="dropdown-item" onclick="openChat(' . $item->id . ')">
                            <i class="flaticon-chat-1" style="padding: 0 10px 0 13px;"></i>
                            <span style="padding-top: 3px">chat</span>
                        </a>';


                if ($chat) {
                    return '<div class="dropdown dropdown-inline">
                            <a href="#" class="btn btn-sm btn-clean btn-icon" data-toggle="dropdown" aria-expanded="true">
                                <i class="la la-ellipsis-h"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">

                                ' . $chat . '


                            </div>
                        </div>';
                } else {
                    return '';
                }
            })
            ->rawColumns(['actions', 'check'])
            ->make();
    }

    public function send_ticket_message_client(Request $request)
    {


        $ticket = Ticket::query()->find($request->ticket_id);
        $message = $request->message;
        $comment = Comment::query()->create([
            'ticket_id' => $ticket->id,
            'created_by' => auth()->user()->id
        ]);


        $comment_reply = CommentReply::query()->create([
            'comment_id' => $comment->id,
            'send_by' => auth()->user()->id,
            'reply_by' => auth()->user()->id,
            'comment' => $request->message
        ]);
        if ($request->has('has_file')) {

            $filenameWithExt = $request->file('file')->getClientOriginalName();

            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);

            $extension = $request->file('file')->getClientOriginalExtension();

            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            $request->file('file')->storeAs('', $fileNameToStore, ['disk' => 'comment_attachments']);
            CommentAttachments::create([
                'path' => $fileNameToStore,
                'name' => $filename,
                'comment_reply_id' => $comment_reply->id
            ]);
        }
        event(new SendTicketMessage($ticket, $message, auth()->user(),$fileNameToStore ?? false));
        return response()->json([
            'success' => true,
            'file_url' => $fileNameToStore ?? false
        ]);
    }

    public function build_chat_client($ticket_id)
    {

        $ticket = Ticket::query()->find($ticket_id);

        $comments = Comment::query()->with('replies.sender')
            ->where('ticket_id', $ticket_id)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'page' => view('m_design.Client.ticket.chat', ['replies' => $ticket->replies()->orderBy('created_at')->get(), 'ticket' => $ticket])->render()
        ]);

    }


}
