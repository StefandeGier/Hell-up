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

        if($tickets){
          return response()->json([
             'tickets' => $tickets,
             'status' => 1
          ]);
        }

    }

    public function getUserTickets()
    {
      //get only the logged in user tickets
      $userTickets = Ticket::where(['user_id' => Auth::user()->id])->with('user')->with('status')->with('tag')->get();

      if($userTickets){
        return response()->json([
           'user_tickets' => $userTickets,
           'status' => 1
        ]);
      }
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      //check valid
        $this->validate($request, [
           'description' => 'required',
       ]);
       //insert the ticket
       $ticket = Ticket::create([
           'description' => request('description'),
           'user_id'     => Auth::user()->id
       ]);

       $tags = array();

      $tagsArr = $request['tags'];

      //insert tags if filled nad not the same
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

       if($ticket){
         return response()->json([
            'tickets' => $ticket,
            'tags'    => $tags,
            'status' => 1
         ]);
       }
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
      //if user is valid to delete
      if(Auth::user()->id == $ticket->user_id){

        $this->validate($request, [
          'description' => 'required',
        ]);
        $ticket->description = request('description');
        $ticket->save();

        if($ticket){
          return response()->json([
             'status' => 1
          ]);
        }

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
      // if user 
      if(Auth::user()->id == $ticket->user_id){
        // delete ticket
        $ticket->delete();

        if($ticket){
          return response()->json([
             'status' => 1
          ]);
      }
    }


    }
}
