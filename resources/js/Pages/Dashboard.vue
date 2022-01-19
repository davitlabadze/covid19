

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
                                        v-model="SelectedCountry"
                                        :settings="{
                                            width:'100%',
                                            ajax: {
                                                url: '/api/countries/list',
                                                dataType: 'json'
                                            },
                                        }"

                                       @select="CountrySelected($event)"
                                        />
                                        <a href="javascript:void(0);"
                                        @click="addVisitedCountry"
                                        class="px-3 ml-3 text-xl font-black text-white bg-green-400 rounded-md hover:bg-green-500">Add</a>
                                    </div>
                                </div>
                                <div>
                                    <div class="items-center justify-between w-full mt-10 mb-5 border border-gray-800 rounded-md ">
                                        <div class="flex justify-between mt-5 ml-24 w-96">
                                            <img  class="w-8" src="https://disease.sh/assets/img/flags/ge.png" alt="bla" />
                                            <h1 class="ml-6 text-xl">{{ choicecountry.country }}</h1>
                                            <a href="javascript:void(0)"
                                                class="flex-1-0"
                                                @click="removeCountry()">
                                                <img class="w-6 ml-10 " src="https://img.icons8.com/fluency/128/000000/filled-trash.png"/>
                                            </a>
                                        </div>
                                       <div class="mb-5 ml-4">
                                            <div class="flex mt-10 font-black text-center">
                                            <div class="h-20 p-2 ml-2 text-yellow-800 bg-yellow-100 border border-gray-800 rounded w-44 1/3">
                                                <h1>Today Cases</h1>
                                                <h2>{{choicecountry.todayCases }}</h2>
                                            </div>
                                            <div class="h-20 p-2 ml-2 text-red-800 bg-red-100 border border-gray-800 rounded w-44 1/3">
                                                <h1>Today Deaths</h1>
                                                <h2>{{choicecountry.todayDeaths }}</h2>
                                            </div>
                                            <div class="h-20 p-2 ml-2 text-green-800 bg-green-100 border border-gray-800 rounded w-44 1/3">
                                                <h1>Today Recovered</h1>
                                                <h2>{{choicecountry.todayRecovered }}</h2>
                                            </div>
                                        </div>
                                       </div>
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
            SelectedCountry: '',
            Countries: [],
            statistics: [],
            choicecountry: [],

        }
    },
    methods: {
        CountrySelected({id, text}){
            // console.log({id, text})
        },

         getCountry(){
            axios.get('/api/countries/visited')
            .then((response) => {
                this.Countries = response.data;
            });
        },
        selectCountry(){
            axios.get('https://disease.sh/v3/covid-19/countries/geo')
            .then((response) => {
                this.choicecountry = response.data;
            });
        },
        addCountry(){

            if (this.SelectedCountry !== '') {
                axios.post('/api/add-visited-country',{
                    countryID: this.SelectedCountry
                }).then((response) => {
                    this.getCountry();
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
            });
        },
        removeCountry(){
            axios.delete('https://disease.sh/v3/covid-19/countries/geo')
            .catch(function(error){
                console.log(error);
            }).then(() =>{
                this.selectCountry();
            });
        },
        initMap(){
            var basic_choropleth = new Datamap({
            element: document.getElementById("map"),
            projection: 'mercator',
                fills: {
                    defaultFill: "#ABDDA4",

                    georgia:"#ff0000",
                },
                data: {

                    GEO: { fillKey: "georgia"},
                }
            });
        },
    },


    created(){
        this.getCountry();
        this.getStatistics();
        this.selectCountry();
        setTimeout(()=> {
            this.initMap();
        }, 1000);
        this.removeCountry();


    }
}
</script>
