<template>

   <div class="container">
       <div class="row">
           <div class="col-md-12">
               <div class="panel panel-default">
                   <div class="panel-heading">
                     <h3><span class="glyphicon glyphicon-dashboard"></span> Assignment Dashboard </h3> <br>
                     <button @click="initAddTicket()" class="btn btn-success " style="padding:5px">
                       Add a new ticket
                     </button>
                   </div>
                   <div class="panel-body">
                     <table class="table table-bordered table-striped table-responsive" v-if="tickets.length > 0">
                           <tbody>
                             <tr>
                                 <th>No.</th>
                                 <th>Description</th>
                                 <th>Action</th>
                             </tr>
                             <tr v-for="(ticket, index) in tickets">
                               <td>{{ index + 1 }}</td>
                               <td>{{ ticket.description}}</td>
                               <td>
                                 <button @click="initUpdate(index)" class="btn btn-success btn-xs" style="padding:8px"><span class="glyphicon glyphicon-edit"></span></button>
                                 <button @click="deleteTicket(index)" class="btn btn-danger btn-xs" style="padding:8px"><span class="glyphicon glyphicon-trash"></span></button>
                               </td>
                             </tr>
                           </tbody>
                       </table>
                    </div>
               </div>
           </div>
       </div>

       <div class="modal fade" tabindex="-1" role="dialog" id="add_ticket_model">
         <div class="modal-dialog" role="document">
           <div class="modal-content">
             <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
               <h4 class="modal-title">Add New Ticket</h4>
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
               <button type="button" @click="createTicket" class="btn btn-primary">Submit</button>
             </div>
           </div><!-- /.modal-content -->
         </div><!-- /.modal-dialog -->
       </div><!-- /.modal -->

       <div class="modal fade" tabindex="-1" role="dialog" id="update_ticket_model">
         <div class="modal-dialog" role="document">
           <div class="modal-content">
             <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                 <h4 class="modal-title">Update Ticket</h4>
             </div>
             <div class="modal-body">
               <div class="alert alert-danger" v-if="errors.length > 0">
                 <ul>
                   <li v-for="error in errors">{{ error }}</li>
                 </ul>
               </div>
               <div class="form-group">
                   <label for="description">Description:</label>
                   <textarea cols="30" rows="5" class="form-control" placeholder="Ticket Description" v-model="update_ticket.description"></textarea>
               </div>
             </div>
             <div class="modal-footer">
               <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
               <button type="button" @click="updateTicket" class="btn btn-primary">Submit</button>
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
           errors: [],
           tickets: [],
           update_ticket: {}
         }
       },
       mounted()
       {
         this.readTickets();
       },
       methods: {
           deleteTicket(index)
           {
             let conf = confirm("Do you ready want to delete this ticket?");
             if (conf === true) {
               axios.delete('/ticket/' + this.tickets[index].id)
               .then(response => {
                 this.tickets.splice(index, 1);
               })
               .catch(error => {
               });
             }
           },
           initAddTicket()
           {
             $("#add_ticket_model").modal("show");
           },
           createTicket()
           {
             axios.post('/ticket', {
               description: this.ticket.description,
             })
             .then(response => {
               this.reset();
               this.tickets.push(response.data.ticket);
               $("#add_ticket_model").modal("hide");
             })
             .catch(error => {
               this.errors = [];
               if (error.response.data.errors && error.response.data.errors.name) {
                 this.errors.push(error.response.data.errors.name[0]);
               }
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
             axios.get('http://127.0.0.1:8000/ticket')
               .then(response => {
                 console.log('Dit is gelukt');
                 this.tickets = response.data.tickets;
               });
           },
           initUpdate(index)
           {
             this.errors = [];
             $("#update_ticket_model").modal("show");
             this.update_ticket = this.tickets[index];
           },
           updateTicket()
           {
             axios.patch('/ticket/' + this.update_ticket.id, {
               description: this.update_ticket.description,
             })
             .then(response => {
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
