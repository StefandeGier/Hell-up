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
                       My Tickets
                     </button>
                   </div>
                   <div class="panel-body">
                     <table class="table-responsive table-bordered table-striped" v-if="tickets.length > 0">
                           <tbody>
                             <tr>
                                 <th>No.</th>
                                 <th>Date</th>
                                 <th>Student</th>
                                 <th>Description</th>
                                 <th>Status</th>
                             </tr>
                             <tr v-for="(ticket, index) in tickets">
                               <td>{{ index + 1 }}</td>
                               <td>{{ ticket.created_at }}</td>
                               <td>{{ ticket.user.firstname}} {{ ticket.user.lastname}}</td>
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
                          <th>Description</th>
                          <th>Status</th>
                          <th>Action</th>
                      </tr>
                      <tr v-for="(user_ticket, index) in user_tickets">
                        <td>{{ index + 1 }}</td>
                        <td>{{ user_ticket.created_at }}</td>
                        <td>{{ user_ticket.user.firstname}} {{ user_ticket.user.lastname}}</td>
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
           </div><!-- /.modal-content -->
         </div><!-- /.modal-dialog -->
       </div><!-- /.modal -->
   </div>
</template>

<script>
   export default {
       data(){
         return {
           ticket: {
               description: ''
           },
           user_ticket: {
              description: ''
           },
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

           deleteTicket(index)
           {
             let conf = confirm("Do you ready want to delete this ticket?");
             if (conf === true) {
               axios.delete('http://hell-up.test/public/ticket/' + this.tickets[index].id)
               .then(response => {
                 //remove from view
                 this.tickets.splice(index, 1);
                 this.user_tickets.splice(index, 1);
               })
               .catch(error => {
               });
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

           createTicket()
           {
             axios.post('http://hell-up.test/public/ticket', {
               description: this.ticket.description,
             })
             .then(response => {
               this.reset();

               this.userTickets();
               this.readTickets();

               //this.tickets.push(response.data.ticket);
               $("#add_ticket_model").modal("hide");
             })
             .catch(error => {
               this.errors = [];
              if (error.response.data.errors && error.response.data.errors.description){
                this.errors.push(error.response.data.errors.description[0]);
              }
             });
           },
           reset()
           {
             this.ticket.description = '';
           },

           readTickets()
           {
             console.log('readTickets')
             axios.get('http://hell-up.test/public/ticket')
               .then(response => {
                 this.tickets = response.data.tickets;
                  console.log(this.tickets);
               });
           },

           userTickets()
           {
             console.log('userTickets')
             axios.get('http://hell-up.test/public/getusertickets/')
               .then(response => {
                 this.user_tickets = response.data.user_tickets;
                 console.log(this.user_tickets.description);
                 console.log(this.user_tickets);
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
             console.log('Update')
             axios.patch('http://hell-up.test/public/ticket/' + this.update_user_ticket.id, {
               description: this.update_user_ticket.description,
             })
             .then(response => {
               this.readTickets();
               //this.userTickets();
               console.log('hide');
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
