

<template>
    <Head title="Dashboard" />
    <BreezeAuthenticatedLayout>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="flex mb-5">
                        <h1 class="w-1/2 text-xl font-black">World Map</h1>
                        <h1 class="w-1/2 text-xl font-black">Global States</h1>
                    </div>

                    <div class="flex">
                        <div class="w-1/2 bg-white">
                            <div>
                                <div class='w-full mt-20 h-96' id="map"></div>
                            </div>
                        </div>
                        <div class="w-1/2 ml-2">
                            <div class="p-2 bg-white rounded shadow ">
                                <div class="flex p-6 bg-white ">
                                   <div class="block w-full p-8 border border-gray-800 rounded">
                                        <div class="flex ">
                                            <img src="https://img.icons8.com/external-soft-fill-juicy-fish/60/000000/external-earth-contact-us-soft-fill-soft-fill-juicy-fish.png"/>
                                            <h3 class="mt-3 ml-5 text-3xl font-black ">World</h3>
                                        </div>

                                        <div class="flex justify-between mt-10 font-black text-center">
                                            <div class="h-20 p-2 ml-2 text-yellow-800 bg-yellow-100 border border-gray-800 rounded w-44 1/3">
                                                <h1>Today Cases</h1>
                                                <h2>{{statistics.todayCases }}</h2>
                                            </div>
                                            <div class="h-20 p-2 ml-2 text-red-800 bg-red-100 border border-gray-800 rounded w-44 1/3">
                                                <h1>Today Deaths</h1>
                                                <h2>{{statistics.todayDeaths }}</h2>

                                            </div>
                                            <div class="h-20 p-2 ml-2 text-green-800 bg-green-100 border border-gray-800 rounded w-44 1/3">
                                                <h1>Today Recovered</h1>
                                                <h2>{{statistics.todayRecovered }}</h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="p-2 bg-white rounded shadow ">
                                <h1 class="px-6 text-xl font-black">Add your Country</h1>
                                <div class="flex p-6 bg-white border-b border-gray-200">
                                    <div class="flex">
                                        <Select2
                                        class=" w-96"
                                        v-model="SelectedVisitedCountry"
                                        :settings="{
                                            width:'100%',
                                            ajax: {
                                                url: '/api/countries/list',
                                                dataType: 'json'
                                            },
                                        }"

                                       @select="VisitedCountrySelected($event)"
                                        />
                                        <a href="javascript:void(0);"
                                        @click="addVisitedCountry"
                                        class="px-3 ml-3 text-xl font-black text-white bg-green-400 rounded-md hover:bg-green-500">Add</a>
                                    </div>
                                </div>
                                <div>
                                    {{ selectCountry.country }}

                                    <div id="content">
                                        <img src="{{ visitedCountries.countryInfo.flag }}" alt="bla" />
                                        <img src="https://disease.sh/assets/img/flags/ge.png" alt="bla" />

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>




    </BreezeAuthenticatedLayout>
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { Head } from '@inertiajs/inertia-vue3';
import Select2 from 'vue3-select2-component';
import axios from 'axios';
export default {
    components: {
        BreezeAuthenticatedLayout,
        Head,
        Select2,
    },
    data() {
        return {
            SelectedVisitedCountry: '',
            visitedCountries: [],
            statistics: [],

        }
    },
    methods: {
        VisitedCountrySelected({id, text}){
            // console.log({id, text})
        },

         getVisitedCountry(){
            axios.get('/api/countries/visited')
            .then((response) => {
                this.visitedCountries = response.data;
            });
        },
        selectCountry(){
            axios.get('https://disease.sh/v3/covid-19/countries/geo')
            .then((response) => {
                this.visitedCountries = response.data;
                console.log(this.visitedCountries.countryInfo.flag);
            });
        },
        addVisitedCountry(){

            if (this.SelectedVisitedCountry !== '') {
                axios.post('/api/add-visited-country',{
                    countryID: this.SelectedVisitedCountry
                }).then((response) => {
                    this.getVisitedCountry();
                }).catch(function(error){
                    console.log(error)
                })
            }else{
                console.log('error');
            }
        },
        getStatistics(){
            axios.get('https://disease.sh/v3/covid-19/all')
            .then((response) => {
                this.statistics = response.data;
                console.log(this.statistics.todayCases);
            });
        },
        removeVisitedCountry(countryID){
            axios.delete('/api/remove-visited-country/'+countryID)
            .catch(function(error){
                console.log(error);
            }).then(() =>{
                this.getVisitedCountry();
            });
        },
        initMap(){
            var basic_choropleth = new Datamap({
            element: document.getElementById("map"),
            projection: 'mercator',
                fills: {
                    defaultFill: "#ABDDA4",
                    authorHasTraveledTo: "#fa0fa0",
                    georgia:"#ff0000",
                },
                data: {
                    USA: { fillKey: "authorHasTraveledTo" },
                    JPN: { fillKey: "authorHasTraveledTo" },
                    ITA: { fillKey: "authorHasTraveledTo" },
                    CRI: { fillKey: "authorHasTraveledTo" },
                    KOR: { fillKey: "authorHasTraveledTo" },
                    DEU: { fillKey: "authorHasTraveledTo" },
                    GEO: { fillKey: "georgia"},
                }
            });
        },
    },


    created(){
        this.getVisitedCountry();
        this.getStatistics();
        setTimeout(()=> {
            this.initMap();
        }, 1000);
        this.removeVisitedCountry();


    }
}
</script>
