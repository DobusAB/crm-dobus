<template>
	<div class="main-container">
		<div class="lead-drawer" v-bind:class="{ 'open': drawerIsOpened}">
      <div class="lead-search-header">
        <select>
          <option> &#x1f64f; - Halmstad</option>
        </select>
        <select>
          <option> &#x1f646; - Frisör</option>
        </select>
        <button class="lead-button medium block-button primary">Sök leads</button>
      </div>
      <p class="info-text">Vi hittade <span>{{items.length}}</span> potentiella leads. Dra ut intressanta leads till höger för att komma igång och boka möten.</p>
  		<div class="lead-card" v-for="item in items" draggable="true">
        <span>&#x1f646;</span>
  			<h3>{{item.companyInfo.companyName}}</h3>
  			<p class="info-text" v-if="item.address.postArea">{{item.address.streetName}}</p>
  			<p v-if="item.companyInfo.companyText">{{item.companyInfo.companyText}}</p>
        <button class="lead-button medium secondary">Spana på Allabolag.se</button>
		<button v-on:click="addLead(item)" class="lead-button medium secondary">Lägg till lead</button>
  		</div>
  		<button class="lead-button medium block-button primary" v-on:click="nextPage">Hämta fler!</button>
		</div>

    <!-- Add class '.open' to open the menu when a lead-card is pressed -->
    <div class="lead-detail" v-bind:class="{ 'open': detailIsOpened}">
        <div class="lead-card">
            <span>&#x1f646;</span>
            <h3>{{detail.company_name}}</h3>
            <p class="info-text">{{detail.address}}</p>
            <form>
              <p class="info-text">Här kan du lägga till uppgifter om din lead. Ju mer du skriver desto större är chansen att din lead nappar.</p>
              <input type="text" placeholder="Namn"></input>
              <input type="text" placeholder="Email"></input>
              <button class="lead-button medium block-button primary">Spara uppgifter</button>
            </form>
        </div>
        <h3>Senaste händelserna</h3>
        <div class="lead-activity align-right">
          <div class="lead-activity-block">
            <span>Inväntar svar</span>
            <span>&#x1f646;</span>
          </div>
          <div class="lead-activity-block">

          </div>
          <div class="lead-activity-block">

          </div>
        </div>
    </div>

    <div class="container" droppable="true" @dragover.prevent @drop="drop">
    <div class="overlay"></div>
    <div class="row lead-header">
        <div class=" col-md-2 col-sm-4 col-xs-4 align-middle lead-filter-wrapper">
          <div class="lead-filter">
            <span>&#x1f646;</span>
            <h2>5</h2>
            <p class="info-text">Du har skickat 5 mail.</p>
          </div>
        </div>
		<button @click="showDrawer()" class="lead-button medium secondary">Öppna företagslådan</button>
    </div>
    <!--- Insert 4 leads in each row -->
  		<div class="row">
        <div v-for="lead in leads" class="col-sm-6 col-md-4 col-lg-3 align-middle">
          <div class="lead-card" @click="showDetail(lead)">
            <span>&#x1f646;</span>
            <h3>{{lead.company_name}}</h3>
            <p class="info-text">{{lead.address}}</p>
			<p class="info-text">{{lead.phone}}</p>
			<p class="info-text">{{lead.email}}</p>
            <div class="lead-status-container align-center align-middle">
              <span class="emoji-icon">🙈</span>Inväntar svar
            </div>
          </div>
        </div>
      </div>
      <!--- Row ended -->
	   </div>
	</div>

</template>
<script>
export default {
  data () {
    return {
	 leads: [],
     items: [],
	 detail: [],
     city: "halmstad",
     startIndex: 1,
	 drawerIsOpened: false,
	 detailIsOpened: false
    }
  },
  methods: {
   showDrawer: function() {
	   this.drawerIsOpened = !this.drawerIsOpened;
   },
   showDetail: function(lead) {
	   this.detailIsOpened = true;
	   this.detail = lead;
   },
   getLeads: function(){
	   this.$http({url: 'http://localhost:8000/api/leads', method: 'GET'}).then(function (response) {
		 this.leads = response.data;
	   }, function (response) {
		 // error callback
	 }.bind(this));
   },
   phoneNumberIsEmpty: function(item) {
	   if (!item.phoneNumbers[0]) {
		//    item.phoneNumbers = [''];
		   return true;
	   }
   },
   addLead: function(item){
	if (this.phoneNumberIsEmpty(item)) {
		item.phoneNumbers = ['']
	}
	item = {
		company_name: item.companyInfo.companyName,
		address: item.address.streetName,
		corporate_identity_number: item.companyInfo.orgNumber,
		phone: item.phoneNumbers[0].phoneNumber,
		contact_person: null,
		email: null,
		homepage: item.homepage
	}
	this.$http.post('http://localhost:8000/api/lead', item).then(function (response) {
		console.log(item);
		this.getLeads();
	}, function (response) {
		//error callback
	});
   },
   getCompanies: function(){
   		this.$http({url: 'http://localhost:8000/api/companies?city='+this.city+'&query=frisör&from=1&to=25', method: 'GET'}).then(function (response) {
          this.items = response.data.adverts;
      	}, function (response) {
          // error callback
      }.bind(this));
   },
   nextPage: function(){

   		var startindex = this.startIndex += 25;
   		var endIndex = startindex + 24;
   		this.$http({url: 'http://localhost:8000/api/companies?city='+this.city+'&query=frisör&from='+startindex+'&to=' + endIndex, method: 'GET'}).then(function (response) {

   			var data = response.data.adverts;
   			var dataLength = response.data.adverts.length;
   			for(var i = 0; i < dataLength; i++)
   			{
   				this.items.push(response.data.adverts[i]);
   			}
      	}, function (response) {
          // error callback
      }.bind(this));

   },
   drop: function(ev){
   		console.log(ev.target);
   }
  },
  ready: function(){
  	this.getCompanies();
	this.getLeads();
  }
}
</script>
