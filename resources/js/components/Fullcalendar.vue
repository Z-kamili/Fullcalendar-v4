<script>

import FullCalendar from '@fullcalendar/vue';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';
import datetime from 'vuejs-datetimepicker';

export default {
  components: {
    FullCalendar,
    datetime
  },
data() 
  {
    return {
      calendarOptions: {
        plugins: [ dayGridPlugin, interactionPlugin ],
        events: "",
        initialView: 'dayGridMonth',
        newEvent: 
        {
          id:"",
          title:"",
          start_date:"",
          end_date:"",
        },
        dateClick:this.handleDateClick,
      },
        errors: [],
        showmodelajouter:false,
        showModal:false,
        Test:false,
    }
  },
  mounted()
  {
    console.log(this.calendarOptions.newEvent.title);
  },
  created(){
    this.getEvents();
  },

  methods: {
  handleDateClick : function(arg)
  {
    this.calendarOptions.newEvent.start_date = arg.dateStr;
    this.calendarOptions.newEvent.end_date = "";
    this.calendarOptions.newEvent.title = "";
    this.showmodelajouter = true;  
  },

  async  getEvents()
  {
  await axios
    .get("/calendar/events")
    .then((resp) => {
      this.calendarOptions.events = resp.data;
    }).catch((err)=>console.log(err.response.data));
  },

  validation()
  {
       this.errors = [];
      if(this.calendarOptions.newEvent.start_date > this.calendarOptions.newEvent.end_date )
      {
         this.errors.push('la Date debut doit etre  plus petit que la date fin.');
         this.Test = false;
      }
      else 
      {
         this.Test = true;
      }
      if(!this.calendarOptions.newEvent.title)
      {
            this.errors.push('le titre et obligatoire.');
            this.Test = false;
      }
      if(!this.calendarOptions.newEvent.start_date)
      {
      
         this.errors.push('la Date debut et obligatoire.');
         this.Test = false;
      }
      if(!this.calendarOptions.newEvent.end_date)
      {
         this.errors.push('la Date fin et obligatoire.');
         this.Test = false;
      }


    },

  addNewEvent() 
  {

      this.validation();
      

      if(this.Test)
      {
       this.calendarOptions.newEvent.id =1;
        axios.post("/calendar/add",{
            ...this.calendarOptions.newEvent,
        }).then((data) => {
            console.log(data);
        }).catch((e)=>{
            console.log(e);
        })
      }

  },

  hideModelAdd:function()
  {
    this.showmodelajouter = false;
  },

  hide:function()
  {
    this.showModal = true;
  },

  }


}

</script>

<template>
<div class="m-auto">
      <!-- add event -->
      <div class="col-md-8" v-if="showmodelajouter">
        <form @submit.prevent>
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Ajouter un evenement</h5>
                <button
                  type="button"
                  class="close"
                  data-dismiss="modal"
                  aria-label="Close">
                  <span v-on:click="this.hideModelAdd" aria-hidden="true">&times;</span>
                </button>
              </div>
               <div class="mt-3 errors">
                  <p v-if="errors.length">
                     <b>Veuillez corriger les erreurs suivante:</b>
                     <ul>
                       <li v-for="error in errors" class="error-item" v-bind:key="error">{{ error }}</li>
                     </ul>
                  </p>
              </div>
              <div class="modal-body">
                <div><label for="">Titre</label></div>
                <input
                  type="text"
                  required
                  v-model="calendarOptions.newEvent.title"
                  class="form-control"
                />
              </div>
              <div class="modal-body">
                <div>
                  <label for="">Date Début</label>
                </div>
                  <datetime  format="YYYY-MM-DD H:i:s"  width="100%" v-model="calendarOptions.newEvent.start_date"></datetime>
              </div>
              <div class="modal-body">
                <div>
                  <label for="">Date Fin</label>
                </div>
                  <datetime  format="YYYY-MM-DD H:i:s"  width="100%" v-model="calendarOptions.newEvent.end_date"></datetime>
              </div>
              <div class="modal-footer">
                <button
                  type="button"
                  class="btn btn-secondary btn-save"
                  data-dismiss="modal"
                  v-on:click="this.hide"
                  @click="addNewEvent"
                >
                 Ajouter
                </button>
                <button
                  type="button"
                  class="btn btn-secondary"
                  data-dismiss="modal"
                >
                  Quitter
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
     <div class="col-md-8">
        <FullCalendar defaultView="dayGridMonth" :options="calendarOptions" />
    </div>
</div>

</template>