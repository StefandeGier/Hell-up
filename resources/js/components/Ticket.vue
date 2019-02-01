<template>

   <div class="container">
        <!--All tickets -->
       <div class="row">
           <div class="col-md-12">
               <div class="panel panel-default">
                   <div class="panel-heading">
                     <h3><span class="glyphicon glyphicon-dashboard"></span> Ticket Dashboard </h3> <br>
                     <button @click="initAddTicket()" class="btn btn-success " style="padding:5px">
                       New
                     </button>
                     <button @click="initUserTickets()" class="btn btn-success " style="padding:5px">
                        Tickets
                     </button>
                   </div>
                   <div class="panel-body">
                     <table class="table-responsive table-bordered table-striped" v-if="tickets.length > 0">
                           <tbody>
                             <tr>
                                 <th>No.</th>
                                 <th>Date</th>
                                 <th>Student</th>
                                 <th>Tags</th>
                                 <th>Description</th>
                                 <th>Status</th>
                             </tr>
                             <tr v-for="(ticket, index) in tickets">
                               <td>{{ index + 1 }}</td>
                               <td>{{ ticket.created_at }}</td>
                               <td>{{ ticket.user.firstname}} {{ ticket.user.lastname}}</td>
                               <td>
                               <span v-if="ticket.tag[0]" class="badge" v-bind:style="{ background: tags[0][ticket.tag[0].tag_id][0].color}">{{tags[0][ticket.tag[0].tag_id][0].name}}</span>
                               <span v-if="ticket.tag[1]" class="badge" v-bind:style="{ background: tags[0][ticket.tag[1].tag_id][0].color}">{{tags[0][ticket.tag[1].tag_id][0].name}}</span>
                               </td>
                               <td>{{ ticket.description}}</td>
                               <td>{{ ticket.status.name}}</td>
                             </tr>
                           </tbody>
                       </table>
                    </div>
               </div>
           </div>
       </div>
       <!--Create modal-->
       <div class="modal fade" tabindex="-1" role="dialog" id="add_ticket_model">
         <div class="modal-dialog" role="document">
           <div class="modal-content">
             <div class="modal-header">
               <h4 class="modal-title">Add New Ticket</h4>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
             </div>
             <div class="modal-body">
               <div class="alert alert-danger" v-if="errors.length > 0">
                 <ul>
                   <li v-for="error in errors">{{ error }}</li>
                 </ul>
               </div>
               <div class="form-group">
                 <label for="description">Description:</label>
                 <textarea name="description" id="description" cols="30" rows="5" class="form-control" placeholder="Ticket Description" v-model="ticket.description"></textarea>
               </div>
               <div class="form-group">
                <label>Tags:</label>
                 <select v-model="selected1" v-on:change="initTag">
                    <option v-for="tag in tags[0]" :value="tag[0].id">{{tag[0].name}}</option>
                </select>

                <select v-model="selected2" v-on:change="initTag">
                    <option v-for="tag in tags[0]" :value="tag[0].id">{{tag[0].name}}</option>
               </select>

              </div>
             </div>
             <div class="modal-footer">
               <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
               <button type="button" @click="createTicket" class="btn btn-primary">Confirm</button>
             </div>
           </div>
         </div>
       </div>
       <!--User tickets -->
       <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="user_ticket_model" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">My tickets</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="panel-body">
              <table class="table-responsive table-bordered table-striped" v-if="user_tickets.length > 0">
                    <tbody>
                      <tr>
                          <th>No.</th>
                          <th>Date</th>
                          <th>Student</th>
                          <th>Tags</th>
                          <th>Description</th>
                          <th>Status</th>
                          <th>Action</th>
                      </tr>
                      <tr v-for="(user_ticket, index) in user_tickets">
                        <td>{{ index + 1 }}</td>
                        <td>{{ user_ticket.created_at }}</td>
                        <td>{{ user_ticket.user.firstname}} {{ user_ticket.user.lastname}}</td>
                        <td>
                          <span v-if="user_ticket.tag[0]" class="badge" v-bind:style="{ background: tags[0][user_ticket.tag[0].tag_id][0].color}">{{tags[0][user_ticket.tag[0].tag_id][0].name}}</span>
                          <span v-if="user_ticket.tag[1]" class="badge" v-bind:style="{ background: tags[0][user_ticket.tag[1].tag_id][0].color}">{{tags[0][user_ticket.tag[1].tag_id][0].name}}</span>
                        </td>
                        <td>{{ user_ticket.description}}</td>
                        <td>{{ user_ticket.status.name}}</td>
                        <td>
                          <button @click="initUpdate(index)" class="btn btn-success btn-xs" style="padding:8px">Edit</button>
                          <button @click="deleteTicket(index)" class="btn btn-danger btn-xs" style="padding:8px">Remove</button>
                        </td>
                      </tr>
                    </tbody>
                </table>
             </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
      <!--update-->
       <div class="modal fade" tabindex="-1" role="dialog" id="update_ticket_model">
         <div class="modal-dialog" role="document">
           <div class="modal-content">
             <div class="modal-header">
              <h4 class="modal-title">Update Ticket</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
             </div>
             <div class="modal-body">
               <div class="alert alert-danger" v-if="errors.length > 0">
                 <ul>
                   <li v-for="error in errors">{{ error }}</li>
                 </ul>
               </div>
               <div class="form-group">
                   <label for="description">Description:</label>
                   <textarea cols="30" rows="5" class="form-control" placeholder="Ticket Description" v-model="update_user_ticket.description"></textarea>
               </div>
             </div>
             <div class="modal-footer">
               <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
               <button type="button" @click="updateTicket" class="btn btn-primary">Confirm</button>
             </div>
           </div>
         </div>
       </div>
   </div>
</template>

<script>
   export default {

       data(){
         return {
           selected1: {},
           selected2: {},
           ticket: {
               description: ''
           },
           user_ticket: {
              description: ''
           },
           tags: [{
             1 :[{ id: '1',  name: 'PHP', color: 'red'}],
             2 :[{ id: '2',  name: 'JS', color: 'blue'}],
             3 :[{ id: '3',  name: 'LARAVEL', color: 'yellow'}],
             4 :[{ id: '4',  name: 'HTML', color: 'green'}],
             5 :[{ id: '5',  name: 'CSS', color: 'purple'}],
             6 :[{ id: '6',  name: 'APPACHE', color: 'red'}],
           }],


           errors: [],
           user_tickets: [],
           tickets: [],
           update_ticket: {},
           update_user_ticket: {}
         }
       },
       mounted()
       {
         this.readTickets();
         this.userTickets();
       },
       methods: {
          //delete the ticket
           deleteTicket(index)
           {
             let conf = confirm("Do you ready want to delete this ticket?");
             if (conf === true) {
               axios.delete('http://hell-up.test/public/ticket/' + this.user_tickets[index].id)
               .then(response => {
                 //remove from view
                 if (!response.data.status) {
                   confirm("Error in delete");
                 }else{
                   this.readTickets();
                   this.user_tickets.splice(index, 1);
                 }
               })
               .catch(error => {
               });
             }
           },
           //give error if same
           initTag()
           {
             if(this.selected1 == this.selected2){
               confirm("Same tags are not allowed");
             }

           },
           initAddTicket()
           {
             $("#add_ticket_model").modal("show");
           },

           initUserTickets()
           {
             $("#user_ticket_model").modal("show");
           },
           //creates a ticket
           createTicket()
           {
             var selected = [this.selected1, this.selected2];

             if(selected.length < 0 || this.selected1 != this.selected2){

               axios.post('http://hell-up.test/public/ticket', {
                 description: this.ticket.description,
                 tags: selected,
               })
               .then(response => {
                 if (!response.data.status) {
                   confirm("Error in create");
                 }else{
                   this.reset();
                   this.userTickets();
                   this.readTickets();
                 }
                 $("#add_ticket_model").modal("hide");
               })
               .catch(error => {
                 this.errors = [];
                if (error.response.data.errors && error.response.data.errors.description){
                  this.errors.push(error.response.data.errors.description[0]);
                }
               });
             }else{
               confirm("Same tags are not allowed");
             }
           },
           reset()
           {
             this.ticket.description = '';
             this.selected1 = '';
             this.selected2 = '';
           },

           readTickets()
           {
             axios.get('http://hell-up.test/public/ticket')
               .then(response => {
                  if (!response.data.status) {
                    confirm("Cannot read tickets");
                  }else {
                    this.tickets = response.data.tickets;
                  }
               });
           },

           userTickets()
           {
             axios.get('http://hell-up.test/public/getusertickets/')
               .then(response => {
                 if (!response.data.status) {
                   confirm("Cannot read tickets");
                 }else {
                   this.user_tickets = response.data.user_tickets;
                 }
               });
           },

           initUpdate(index)
           {
             this.errors = [];
             $("#update_ticket_model").modal("show");
             this.update_user_ticket = this.user_tickets[index];
           },

           updateTicket()
           {
             axios.patch('http://hell-up.test/public/ticket/' + this.update_user_ticket.id, {
               description: this.update_user_ticket.description,
             })
             .then(response => {
               if (!response.data.status) {
                 confirm("Cannot read tickets");
               }else {
                 this.readTickets();
               }
               $("#update_ticket_model").modal("hide");
             })
             .catch(error => {
               this.errors = [];
               if (error.response.data.errors.description) {
                 this.errors.push(error.response.data.errors.description[0]);
               }
             });
           }
       }
   }

</script>
