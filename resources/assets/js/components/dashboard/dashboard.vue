<template>
	<div class="main-container" @dragover.prevent @drop="drop">
		<div class="lead-drawer">
      <div class="lead-search-header">
        <select>
          <option> &#x1f64f; - Halmstad</option>
        </select>
        <select>
          <option> &#x1f646; - Frisör</option>
        </select>
        <button class="lead-button block-button primary">Sök leads</button>
      </div>
      <p class="info-text">Vi hittade <span>{{items.length}}</span> potentiella leads. Dra ut intressanta leads till höger för att komma igång och boka möten.</p>
  		<div class="lead-card" v-for="item in items" draggable="true">
        <span>&#x1f646;</span>
  			<h3>{{item.companyInfo.companyName}}</h3>
  			<p class="info-text" v-if="item.address.postArea">{{item.address.streetName}}</p>
  			<p v-if="item.companyInfo.companyText">{{item.companyInfo.companyText}}</p>
        <button class="lead-button secondary">Spana på Allabolag.se</button>
  		</div>
  		<button class="lead-button block-button primary" v-on:click="nextPage">Hämta fler!</button>
		</div>

    <div class="lead-detail">
        <div class="lead-card">
            <span>&#x1f646;</span>
            <h3>Häftiga företaget</h3>
            <p class="info-text">Glada gatan</p>
            <form>
              <p class="info-text">Här kan du lägga till uppgifter om din lead. Ju mer du skriver desto större är chansen att din lead nappar.</p>
              <input type="text" placeholder="Namn"></input>
              <input type="text" placeholder="Email"></input>
              <button class="lead-button block-button primary">Spara uppgifter</button>
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

    <div class="container">

    <div class="row lead-header">
      
    </div>
    <!--- Insert 4 leads in each row -->
  		<div class="row">
        <div class="col-sm-6 col-md-4 col-lg-3 align-middle"> 
          <div class="lead-card">
            <span>&#x1f646;</span>
            <h3>Häftiga företaget</h3>
            <p class="info-text">Glada gatan</p>
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
     items: [],
     city: "halmstad",
     startIndex: 1,
     isA: true,
     isB: false
    }
  },
  methods: {
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
  }
}
</script>