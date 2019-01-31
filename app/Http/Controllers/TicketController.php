<?php

namespace App\Http\Controllers;

use App\Ticket;
use App\Tag;
use Auth;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function __construct()
    {

        $this->middleware('auth');

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get all Ticket and from other tables status, tag, and user
        $tickets = Ticket::with('user')->with('status')->with('tag')->get();

        return response()->json([
           'tickets' => $tickets,
        ], 200);
    }

    public function getUserTickets()
    {
      //get only the logged in user tickets
      $userTickets = Ticket::where(['user_id' => Auth::user()->id])->with('user')->with('status')->with('tag')->get();

      return response()->json([
         'user_tickets'    => $userTickets,
      ], 200);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
           'description' => 'required',
       ]);

       $ticket = Ticket::create([
           'description' => request('description'),
           'user_id'     => Auth::user()->id
       ]);

       $tags = array();

      $tagsArr = $request['tags'];

      
       if($tagsArr[0] != $tagsArr[1] && empty(!$tagsArr)){
         foreach ($tagsArr as $tag) {
           if($tag != null){
             $tags[] = Tag::create([
                  'ticket_id' => $ticket->id,
                  'tag_id' => $tag,
             ]);
           }
         }
       }
       return response()->json([
           'ticket'    => $ticket,
           'tags'    => $tags,
           'message' => 'Success'
       ], 200);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, Ticket $ticket)
    {

      if(Auth::user()->id == $ticket->user_id){

        $this->validate($request, [
          'description' => 'required',
        ]);
        $ticket->description = request('description');
        $ticket->save();

        return response()->json([
          'message' => 'Ticket updated successfully!',
        ], 200);
      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ticket $ticket)
    {
        $ticket->delete();

        return response()->json([
          'message' => 'Ticket deleted successfully!',
        ], 200);
    }
}
