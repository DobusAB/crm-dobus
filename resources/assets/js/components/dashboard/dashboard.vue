<template>
	<div class="main-container">
		<div class="lead-wrapper">

		<div class="lead" v-for="item in items" draggable="true">
			<h2>{{item.companyInfo.companyName}}</h2>
			<ul>
				<li v-if="item.address.postArea">{{item.address.postArea}} {{item.address.postCode}} {{item.address.streetName}}</li>
				<li v-if="item.companyInfo.orgNumber">{{item.companyInfo.orgNumber}}</li>

				<li v-if="item.companyInfo.companyText">{{item.companyInfo.companyText}}</li>
			</ul>
		</div>
		<button v-on:click="nextPage"class="ui button">Hämta fler!</button>
		</div>

		<div class="lead-wrapper" @dragover.prevent @drop="drop">	
		</div>
		<div class="lead-wrapper">
		</div>
		<div class="lead-wrapper">
		</div>
		<div class="lead-wrapper">
			
			
		</div>
	</div>
</template>
<script>
export default {
  data () {
    return {
     items: [],
     city: "halmstad",
     startIndex: 1
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