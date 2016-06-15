<template>
  <div class="main-container">
    <div class="lead-drawer" v-bind:class="{ 'open': drawerIsOpened}">
      <div class="lead-search-header">
        <select v-model="selectedCity" @change="getCompanies()">
          <option v-for="option in cityOptions" v-bind:value="option.value">
            {{ option.text }}
          </option>
        </select>
        <span>{{selectedCity}}</span>
        <select v-model="selectedIndustry" @change="getCompanies()">
          <option v-for="option in industryOptions" v-bind:value="option.value">
            {{ option.text }}
          </option>
        </select>
        <span>{{selectedIndustry}}</span>
        <button class="lead-button medium block-button primary">Sök leads</button>
      </div>
      <p class="info-text">Vi hittade <span>{{items.length}}</span> potentiella leads. Dra ut intressanta leads till höger för att komma igång och boka möten.</p>
      <div class="lead-card" v-for="item in items" draggable="true">
        <span>&#x1f646;</span>
        <h3>{{item.companyInfo.companyName}}{{item.email}}</h3>
        <p class="info-text" v-if="item.address.postArea">{{item.address.streetName}}</p>
        <p v-if="item.companyInfo.companyText">{{item.companyInfo.companyText}}</p>
        <button class="lead-button medium secondary" v-on:click="allaBolag(item)">Spana på Allabolag.se</button>
        <button v-on:click="addLead(item)" class="lead-button image-button fixed-button secondary">
          <svg width="36px" height="36px" viewBox="365 846 36 36" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
              <g id="Group-11" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" transform="translate(365.000000, 846.000000)">
                  <rect id="Rectangle-31" fill="#FFFFFF" x="14" y="0" width="8" height="36" rx="4"></rect>
                  <rect id="Rectangle-31" fill="#FFFFFF" transform="translate(18.000000, 18.000000) rotate(-90.000000) translate(-18.000000, -18.000000) " x="14" y="0" width="8" height="36" rx="4"></rect>
              </g>
          </svg>
        </button>
      </div>
      <button class="lead-button medium block-button primary" v-on:click="nextPage">Hämta fler!</button>
    </div>

    <!-- Add class '.open' to open the menu when a lead-card is pressed -->
    <div class="lead-detail" v-bind:class="{ 'open': detailIsOpened}">
        <div class="lead-card">
            <span>&#x1f646;</span>
            <h3>{{detail.company_name}}</h3>
            <p class="info-text">{{detail.address}}</p>
      <p class="info-text">{{detail.phone}}</p>
      <p class="info-text">{{detail.email}}</p>
            <form>
              <p class="info-text">Här kan du lägga till uppgifter om din lead.</p>
              <input type="text" placeholder="Kontaktperson" value="{{detail.contact_person}}"></input>
              <input type="text" placeholder="Email" value="{{detail.email}}"></input>
              <button class="lead-button medium block-button primary">Spara uppgifter</button>
            </form>
        </div>
<!--         <h3>Senaste händelserna</h3>
        <div class="lead-activity align-right">
          <div class="lead-activity-block">
            <span>Inväntar svar</span>
            <span>&#x1f646;</span>
          </div>
          <div class="lead-activity-block">

          </div>
          <div class="lead-activity-block">

          </div>
        </div> -->
    </div>

    <div class="container" droppable="true" @dragover.prevent @drop="drop">
    <div class="overlay" @click="closeOverlay()" v-bind:class="{ 'open': overlayIsShowing}"></div>
    <div class="row lead-header">
        <div class=" col-md-2 col-sm-4 col-xs-4 align-middle lead-filter-wrapper">
          <div class="lead-search" @click="showDrawer()">
              <svg width="37px" height="38px" viewBox="-4 55 37 38" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
    <defs></defs>
    <path d="M32.2968832,87.9027548 L23.1303003,78.7366518 C24.8088645,76.381766 25.800067,73.5051188 25.800067,70.399991 C25.800067,62.4598111 19.3402109,55.999955 11.400031,55.999955 C3.45985115,55.999955 -3.000005,62.4598111 -3.000005,70.399991 C-3.000005,78.3401709 3.45985115,84.800027 11.400031,84.800027 C14.5051588,84.800027 17.381806,83.8088245 19.7366918,82.1302603 L28.9032748,91.2968432 C29.3712759,91.7658044 29.9861575,92.000045 30.600079,92.000045 C31.2140005,92.000045 31.8288821,91.7658044 32.2968832,91.2973232 C33.2343256,90.3598809 33.2343256,88.8401971 32.2968832,87.9027548 L32.2968832,87.9027548 Z M11.400031,80.000015 C6.09793774,80.000015 1.800007,75.7020843 1.800007,70.399991 C1.800007,65.0978977 6.09793774,60.799967 11.400031,60.799967 C16.7021243,60.799967 21.000055,65.0978977 21.000055,70.399991 C21.000055,75.7020843 16.7021243,80.000015 11.400031,80.000015 L11.400031,80.000015 Z" id="Shape" stroke="none" fill="#FFFFFF" fill-rule="evenodd"></path>
              </svg>
          </div>
        </div>
        <div class=" col-md-2 col-sm-4 col-xs-4 align-middle lead-filter-wrapper">
          <div class="lead-filter">
            <span>&#x1f646;</span>
            <h2>5</h2>
            <p class="info-text">Du har skickat 5 mail.</p>
          </div>
        </div>
    </div>
    <!--- Insert 4 leads in each row -->
      <div class="row">
        <div v-for="lead in leads" class="col-sm-6 col-md-4 col-lg-3 align-middle">
          <div class="lead-card" @click="showDetail(lead)">
            <span>&#x1f646;</span>
            <h3>{{lead.company_name}}</h3>
      <p class="info-text">{{lead.address}}</p>
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
      startIndex: 1,
      drawerIsOpened: false,
      detailIsOpened: false,
      overlayIsShowing: false,

      selectedCity: 'Halmstad',
      cityOptions: [
        { text: 'Halmstad', value: 'Halmstad' },
        { text: 'Malmö', value: 'Malmö' },
        { text: 'Göteborg', value: 'Göteborg' }
      ],
      selectedIndustry: 'Frisör',
      industryOptions: [
        { text: 'Frisör', value: 'Frisör' },
        { text: 'Webb', value: 'Webb' },
        { text: 'Tandläkare', value: 'Tandläkare' }
      ],
    }
  },
  methods: {
   closeOverlay: function() {
     this.detailIsOpened = false;
     this.drawerIsOpened = false;
     this.overlayIsShowing = false;
   },
   showDrawer: function() {
     this.drawerIsOpened = !this.drawerIsOpened;
     this.overlayIsShowing = true;
   },
   showDetail: function(lead) {
     this.detailIsOpened = true;
     this.overlayIsShowing = true;
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
   // allaBolag: function (item) {
   //  var name = encodeURIComponent(item.companyInfo.companyName);
   //  var city = encodeURIComponent(this.selectedCity);
   //  var url = 'http://www.allabolag.se/?what=' + name + '&where=' + city;
   //  var win = window.open(url, '_blank');
   //  win.focus();
   // },
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

  this.closeOverlay();
   },
   getCompanies: function(){
      this.$http({url: 'http://localhost:8000/api/companies?city=' + this.selectedCity + '&query=' + this.selectedIndustry + '&from=1&to=25', method: 'GET'}).then(function (response) {
          this.items = response.data;
          console.log(this.items);  
        }, function (response) {
          // error callback
      }.bind(this));
   },
   nextPage: function(){

      var startindex = this.startIndex += 25;
      var endIndex = startindex + 24;
      this.$http({url: 'http://localhost:8000/api/companies?city=' + this.selectedCity + '&query=' + this.selectedIndustry + '&from=' + startindex + '&to=' + endIndex, method: 'GET'}).then(function (response) {

        var data = response.data;
        var dataLength = response.data.length;
        for(var i = 0; i < dataLength; i++)
        {
          this.items.push(response.data[i]);
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
