<template>
    <div class="relative bg-gray-200 min-h-screen p-4 flex flex-col gap-4 w-full">
        <div class="w-full bg-white rounded-xl flex flex-col divide-y-2 shadow-md">
            <!-- Header Start -->
            <div class="p-4 flex items-center justify-between">
                <h2 class="text-2xl font-bold">Grafik Standar</h2>

                <!-- Filter and Button -->
                <div class="flex items-center gap-4">
                    <!-- High - Low Filter -->
                    <select class="border-2 border-[#cccccc] p-2 rounded" @change="filter(value)" v-model="value" v-if="cvalue == 2">
                        <option disabled class="text-white">Filter</option>
                        <option :value="1">High -> Low</option>
                        <option :value="2">Low -> High</option>
                    </select>

                    <!-- Semester Filter -->
                    <select class="border-2 border-[#cccccc] p-2 rounded" @change="req" v-model="sem" v-if="authStore.idParams.semester == 'ALL'">
                        <option disabled>Pilih Semester</option>
                        <option value="ganjil">Ganjil</option>
                        <option value="genap">Genap</option>
                    </select>

                    <!-- Back Button -->
                    <button @click="goBack" class="flex items-center gap-2 bg-neutral-400 hover:bg-neutral-500 shadow-md text-white py-2 px-4 rounded">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
                        </svg>
                        Back
                    </button>
                </div>
            </div>
            <!-- Header End -->

            <!-- Content Start -->
            <div v-if="prodiDatas && prodiDatas.length" class="p-4 flex flex-col gap-4">
                <div class="w-full flex items-center justify-end">
                    <!-- Select Option Start -->
                    <select v-model="typeChart" @change="changeChart" class="border border-2 border-[#cccccc] p-2 rounded w-fit">
                        <option value="bar">Bar</option>
                        <option value="radar">Radar</option>
                        <option value="line">Line</option>
                    </select>
                    <!-- Select Option End -->
                </div>
                <div v-for="(item, index) in prodiDatas" :key="index" class="flex flex-col gap-4">
                    <h3 class="text-2xl font-semibold mb-4">{{ item.jenis_standar }}</h3>

                    <!-- Chart Start -->
                    <div class="h-full">
                        <!-- Bar Chart -->
                        <div class="bg-[#F8F8FF] p-4 rounded-lg shadow-md h-[500px]" v-if="typeChart === 'bar'">
                            <h4 class="text-lg font-medium mb-2">Bar Chart</h4>
                            <div class="flex items-center justify-center w-full h-full max-h-full pb-10">
                                <canvas :id="'bar-' + item.jenis_standar.replace(/\s/g, '')"></canvas>
                            </div>
                        </div>

                        <!-- Radar Chart -->
                        <div class="bg-[#F8F8FF] p-4 rounded-lg shadow-md h-[500px]" v-if="typeChart === 'radar'">
                            <h4 class="text-lg font-medium mb-2">Radar Chart</h4>
                            <div class="flex items-center justify-center w-full h-full max-h-full pb-10">
                                <canvas :id="'spider-' + item.jenis_standar.replace(/\s/g, '')"></canvas>
                            </div>
                        </div>

                        <!-- Line Chart -->
                        <div class="bg-[#F8F8FF] p-4 rounded-lg shadow-md h-[500px]" v-if="typeChart === 'line'">
                            <h4 class="text-lg font-medium mb-2">Line Chart</h4>
                            <div class="flex items-center justify-center w-full h-full max-h-full pb-10">
                                <canvas :id="'line-' + item.jenis_standar.replace(/\s/g, '')"></canvas>
                            </div>
                        </div>
                    </div>
                    <!-- Chart End -->
                </div>
            </div>
            <!-- Content Start -->
        </div>
    </div>
</template>

<script setup>
import {onMounted, ref, nextTick} from 'vue';
import Chart from 'chart.js/auto';
import axios from "axios";
import {useAuthStore} from "@/router/auth.js";
import ChartJSPluginDatalabels from "chartjs-plugin-datalabels";
import router from "@/router/index.js";
Chart.register(ChartJSPluginDatalabels);
const authStore = useAuthStore();
const prodiDatas = ref(null);
const value = ref(null);
const cvalue = ref(null);
const charts = ref({});
const typeChart = ref('');
const sem = ref('');
document.title = `Graph | ${authStore.name}`;

const req = onMounted(async () => {
    typeChart.value = 'bar';
    try {
        if(sem.value == '' && authStore.idParams.semester == 'ALL'){
            sem.value = "ganjil";
        }else if(authStore.idParams.semester != 'ALL'){
            sem.value = authStore.idParams.semester
        }
        const response = await axios.get(`${authStore.url}/grafik/${authStore.idParams.tipeAudit}/${authStore.idParams.tahun}/${sem.value}`,authStore.header);
        prodiDatas.value = response.data;
        await nextTick();
        renderCharts(prodiDatas.value);
    } catch (error) {
        console.error('Error fetching data:', error);
    }
});

const filter = async (option) => {
    if (option == 1 || option == 2) {
        prodiDatas.value.sort((a, b) => {
            const capaianA = a.capaian;
            const capaianB = b.capaian;

            let scoreA = 0;
            let scoreB = 0;

            for (let i = 0; i < capaianA.length; i++) {
                if (capaianA[i] > capaianB[i]) {
                    scoreA++;
                } else if (capaianA[i] < capaianB[i]) {
                    scoreB++;
                }
            }

            return option == 1 ? scoreB - scoreA : scoreA - scoreB; // sorting in descending or ascending order
        });
    }

    // Await for the next tick to ensure reactivity
    await nextTick();

    // Render the charts with the updated data
    renderCharts(prodiDatas.value);
};

const goBack = () => {
    router.push({name: "VisualAuditAsAdmin"});
};
const changeChart = async () => {
    await nextTick();
    renderCharts(prodiDatas.value);
}
function renderCharts(datas) {
    // Destroy existing charts before creating new ones
    if (charts && charts.value) {
        for (const key in charts.value) {
            if (charts.value[key]) {
                charts.value[key].destroy();
            }
        }
        charts.value = {};
    } else {
        charts.value = {};
    }

    datas.forEach((item, index) => {
        const datasetsBar = []; // Dataset khusus untuk tipe chart bar
        const datasets = []; // Dataset untuk tipe chart radar atau line

        const prodiArray = item.prodi; // Array nama prodi
        const capaianArray = item.capaian; // Array capaian per prodi

        const canvasId = `bar-${item.jenis_standar.replace(/\s/g, '')}`;
        const canvasId1 = `spider-${item.jenis_standar.replace(/\s/g, '')}`;
        const canvasId2 = `line-${item.jenis_standar.replace(/\s/g, '')}`;
        let ctx, ctx1, ctx2;

        if (typeChart.value == 'bar') {
            ctx = document.getElementById(canvasId).getContext('2d');
        } else if (typeChart.value == 'radar') {
            ctx1 = document.getElementById(canvasId1).getContext('2d');
        } else {
            ctx2 = document.getElementById(canvasId2).getContext('2d');
        }

        // Generate random colors for each dataset
        const backgroundColors = [];
        const borderColors = [];
        prodiArray.forEach(() => {
            const randomColor = `rgba(${Math.floor(Math.random() * 256)}, ${Math.floor(Math.random() * 256)}, ${Math.floor(Math.random() * 256)}, 0.5)`;
            backgroundColors.push(randomColor);
            borderColors.push(randomColor.replace('0.5', '1'));
        });

        // Bar Chart specific datasets
        prodiArray.forEach((prodi, i) => {
            datasetsBar.push({
                label: prodi,
                data: [parseFloat(capaianArray[i])],
                backgroundColor: backgroundColors[i],
                borderColor: borderColors[i],
                borderWidth: 1
            });
        });

        // Common datasets for radar and line charts
        datasets.push({
            label: 'Capaian',
            data: capaianArray,
            backgroundColor: backgroundColors,
            borderColor: borderColors,
            borderWidth: 1,
            fill: true
        });

        if (typeChart.value == 'bar') {
            // Bar Chart
            charts.value[canvasId] = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['CAPAIAN PRODI'],
                    datasets: datasetsBar
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            align: 'end'
                        },
                        datalabels: {
                            anchor: 'end',
                            align: 'end',
                            color: 'black',
                            clamp: true,
                            formatter: (value, context) => {
                                return value; // Pastikan formatter mengembalikan nilai yang benar
                            },
                        }
                    }
                }
            });
        } else if (typeChart.value == 'radar') {
            // Radar Chart
            charts.value[canvasId1] = new Chart(ctx1, {
                type: 'radar',
                data: {
                    labels: prodiArray,
                    datasets: datasets
                },
                options: {
                    scales: {
                        r: {
                            beginAtZero: true,
                            max: 100
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            align: 'end'
                        },
                        datalabels: {
                            anchor: 'end',
                            align: 'center',
                            color: 'black',
                            formatter: (value, context) => {
                                return value; // Menampilkan nilai di atas batang
                            }
                        }
                    }
                }
            });
        } else {
            // Line Chart
            charts.value[canvasId2] = new Chart(ctx2, {
                type: 'line',
                data: {
                    labels: prodiArray,
                    datasets: datasets
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100
                        }
                    },
                    elements: {
                        line: {
                            borderWidth: 3
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            align: 'end'
                        },
                        datalabels: {
                            anchor: 'end',
                            align: 'center',
                            color: 'black',
                            formatter: (value, context) => {
                                return value; // Menampilkan nilai di atas batang
                            }
                        }
                    }
                }
            });
        }
    });
}
</script>
