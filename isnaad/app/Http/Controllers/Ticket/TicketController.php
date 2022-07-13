<?php

namespace App\Http\Controllers\Ticket;


use App\Events\SendTicketMessage;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\CommentReply;
use App\Models\Ticket;
use App\Models\TicketAssignedTo;
use App\Models\TicketFiles;
use App\Models\TicketStatus;
use App\user;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;


class TicketController extends Controller
{

    public function index()
    {
        return view('m_design.Ticket.index');
    }

    public function list()
    {


        $ticket = Ticket::query()->with('status')->with('store');

        if (!auth()->user()->can('ticket_assign')) {
            $ticket->whereHas('user_assigned', function ($q) {
                $q->where('user_id', auth()->user()->id);
            });
        }

        return DataTables::of($ticket)
            ->addColumn('actions', function ($item) {

                $confirm = '';

                $chat = '<a href="javascript:;" class="dropdown-item" onclick="openChat(' . $item->id . ')">
                            <i class="flaticon-chat-1" style="padding: 0 10px 0 13px;"></i>
                            <span style="padding-top: 3px">chat</span>
                        </a>';
                $view = '<a href="javascript:;" class="dropdown-item" onclick="showModal(  \'' . route('admin_ticket.view', ['ticket' => $item->id]) . '\')">
                            <i class="flaticon-eye" style="padding: 0 10px 0 13px;"></i>
                            <span style="padding-top: 3px">view</span>
                        </a>';


                if (auth()->user()->can('ticket_assign') && $item->status_id != 2) {
                    $assign = '<a href="javascript:;" onclick="showModal(  \'' . route('admin_ticket.assign_form', ['ticket_id' => $item->id]) . '\')" class="dropdown-item" ">
                            <i class="flaticon2-correct" style="padding: 0 10px 0 13px;"></i>
                            <span style="padding-top: 3px">assign</span>
                        </a>';
                } else {
                    $assign = '';
                }


                if ($chat) {
                    return '<div class="dropdown dropdown-inline">
                            <a href="#" class="btn btn-sm btn-clean btn-icon" data-toggle="dropdown" aria-expanded="true">
                                <i class="la la-ellipsis-h"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">

                                ' . $chat . '
                                ' . $assign . '
                                ' . $view . '
                            </div>
                        </div>';
                } else {
                    return '';
                }
            })
            ->rawColumns(['actions', 'check'])
            ->make();
    }

    public function viewTicket(Ticket $ticket)
    {

        return response()->json([
            'success' => true,
            'page' => view('m_design.Ticket.view', compact('ticket'))->render()
        ]);
    }

    public function downloadTikcetFile(TicketFiles $file)
    {
        return response()->download('ticket/' . $file->path);
    }

    public function save_token(Request $request)
    {
        auth()->user()->update([
            'pc_device_token' => $request->token
        ]);
    }

    public function build_chat($ticket_id)
    {
        $ticket = Ticket::query()->find($ticket_id);
//        $replies = $ticket->replies()->orderBy('created_at', 'DESC')->get();

        return response()->json([
            'success' => true,
            'page' => view('m_design.layout.chat', ['replies' => $ticket->replies()->orderBy('created_at')->get(), 'ticket' => $ticket]
            )->render()
        ]);
    }

    public function send_ticket_message(Request $request)
    {

        $ticket = Ticket::query()->find($request->ticket_id);


        $message = $request->message;
        $comment = Comment::query()->whereHas('created_by', function ($q) {
            $q->where('type', 'a');
        })->take(1)->first();

        $comment = Comment::query()->create([
            'ticket_id' => $ticket->id,
            'created_by' => auth()->user()->id
        ]);

        CommentReply::query()->create([
            'comment_id' => $comment->id,
            'send_by' => auth()->user()->id,
            'reply_by' => auth()->user()->id,
            'comment' => $request->message
        ]);
        event((new SendTicketMessage($ticket, $message, auth()->user()))->dontBroadcastToCurrentUser());
    }

    public function assign_form($ticket_id)
    {

        $ticket = Ticket::query()->with('user_assigned')->find($ticket_id);

        $admins = user::query()->where('type', '!=', 'a')->get();
        $assigned_user = $ticket->user_assigned->pluck('id')->toArray();

        return response()->json([
            'success' => true,
            'page' => view('m_design.Ticket.assign_form', ['assigned_user' => $assigned_user, 'ticket' => $ticket, 'admins' => $admins])->render()
        ]);

    }

    public function assign_user(Request $request)
    {

        $ticket = Ticket::query()->find($request->id);
        TicketAssignedTo::query()->where('ticket_id', $ticket->id)->delete();
        if (is_array($request->users) && count($request->users)) {
            foreach ($request->users as $user) {
                TicketAssignedTo::query()->create([
                    'ticket_id' => $ticket->id,
                    'user_id' => $user,
                    'assigned_by' => auth()->user()->id
                ]);
            }

        }
        return response()->json([
            'success' => TRUE,
            'message' => 'users assigned successfully'
        ]);


    }

    public function close_ticket($id)
    {
        $ticket = Ticket::query()->findOrFail($id);
        $status = TicketStatus::query()->where('name', 'closed')->first();
        tap($ticket->update([
            'status_id' => $status->id
        ]));
        return response()->json([
            'success' => true,
            'message' => 'ticket closed successfully'
        ]);
    }

}
