<template>
    <!-- <tbody id = "displayTableVisitors">
  </tbody> -->
    <Bar v-if="loaded" :chart-data="chartData" :chart-options="chartOptions" />
</template>

<script>
import Vue from "vue";
import axios from "axios";
import VueAxios from "vue-axios";
import { Bar } from "vue-chartjs/legacy";
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
} from "chart.js";

ChartJS.register(
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale
);

Vue.use(VueAxios, axios);
export default {
    name: "HourlyChat",
    components: { Bar },
    data: () => ({
        loaded: false,
        chartData: null,
        chartOptions: {
            responsive: true,
            plugins: {
                legend: {
                    display: false,
                },
            },
            scales: {
                x: {
                    grid: {
                        display: false,
                    },
                },
            },
        },
    }),
    mounted() {
        Vue.axios.get("/api/reports/chats/todays-hourly ").then((resp) => {
            //   this.divContainer = document.getElementById("displayTableVisitors");
            //   this.divContainer.innerHTML = "";

            //   let visitorsHr = Object.keys(resp.data.data);

            //   visitorsHr.forEach((key) => {
            //        this.divContainer.innerHTML += `<tr style="border: 1px solid gray;">
            //           <td style="border: 1px solid gray; padding: 0px 5px 0px 5px">${key}</td>
            //           <td style="border: 1px solid gray; padding: 0px 5px 0px 5px">${resp.data.data[key]}</td>
            //       </tr>`;
            //   });

            //   console.warn(this.divContainer);

            const labels = Object.keys(resp.data.data);
            this.chartData = {
                labels: labels,
                datasets: [{ data: labels.map((l) => resp.data.data[l]) }],
            };
            this.loaded = true;
        });
    },
};
</script>
