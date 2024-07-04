<template>
    <div class="bg-gray-200 min-h-screen p-4 flex flex-col gap-4 w-full">
        <div class="w-full h-full bg-white rounded-xl flex flex-col divide-y-2 shadow-md">
            <!-- Header Start -->
            <div class="p-4 flex items-center justify-between">
                <h2 class="text-2xl font-bold">Grafik Audit</h2>

                <div class="flex items-center gap-4">
                    <!-- Visualization Button -->
                    <button class="py-2 px-4 rounded bg-blue-500 w-fit cursor-pointer text-white hover:bg-blue-700 shadow-md" @click="visualStandar">Visualisasi Standar</button>

                    <!-- Back Button -->
                    <div class="flex justify-between">
                        <button @click="goBack" class="flex items-center gap-2 bg-neutral-400 hover:bg-neutral-500 shadow-md text-white py-2 px-4 rounded">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
                            </svg>
                            Back
                        </button>
                    </div>
                </div>
            </div>
            <!-- Header End -->

            <!-- Content Start -->
            <div class="p-4 h-full flex flex-col gap-4">
                <div>
                    <h2 v-if="authStore.idParams.semester === 'ALL'" class="text-2xl font-bold capitalize">{{ authStore.idParams.tipeAudit }} - {{ authStore.idParams.tahun }} - Semua Semester</h2>
                    <h2 v-else class="text-2xl font-bold capitalize">{{ authStore.idParams.tipeAudit }} - {{ authStore.idParams.tahun }} - Semester {{ authStore.idParams.semester }}</h2>
                </div>
                <!-- Select Option Start -->
                <div class="w-full flex justify-end">
                    <select v-model="typeChart" @change="changeChart" class="border border-2 border-[#cccccc] p-2 rounded">
                        <option value="bar">Bar</option>
                        <option value="radar">Radar</option>
                        <option value="line">Line</option>
                    </select>
                </div>
                <!-- Select Option End -->

                <!-- Chart Start -->
                <div class="h-full">
                    <!-- Bar Chart -->
                    <div class="bg-[#F8F8FF] p-4 rounded-lg shadow-md h-[500px]" v-if="typeChart === 'bar'">
                        <h4 class="text-lg font-medium mb-2">Bar Chart</h4>
                        <div class="flex items-center justify-center w-full h-full max-h-full pb-10">
                            <canvas id="bar-0"></canvas>
                        </div>
                    </div>

                    <!-- Radar Chart -->
                    <div class="bg-[#F8F8FF] p-4 rounded-lg shadow-md h-[500px]" v-if="typeChart === 'radar'">
                        <h4 class="text-lg font-medium mb-2">Radar Chart</h4>
                        <div class="flex items-center justify-center w-full h-full max-h-full pb-10">
                            <canvas id="spider-0"></canvas>
                        </div>
                    </div>

                    <!-- Line Chart -->
                    <div class="bg-[#F8F8FF] p-4 rounded-lg shadow-md h-[500px]" v-if="typeChart === 'line'">
                        <h4 class="text-lg font-medium mb-2">Line Chart</h4>
                        <div class="flex items-center justify-center w-full h-full max-h-full pb-10">
                            <canvas id="line-0"></canvas>
                        </div>
                    </div>
                    <!-- Additional charts can be added dynamically as needed -->
                </div>
                <!-- Chart End -->
            </div>
            <!-- Content End -->
        </div>
    </div>
</template>

<script setup>
import { onMounted, ref, nextTick } from 'vue';
import Chart from 'chart.js/auto';
import axios from "axios";
import { useAuthStore } from "@/router/auth.js";
import ChartJSPluginDatalabels from "chartjs-plugin-datalabels";
import router from "@/router/index.js";

Chart.register(ChartJSPluginDatalabels);

const authStore = useAuthStore();
const prodiDatas = ref(null);
const charts = ref({});
const typeChart = ref('');
document.title = `Graph | ${authStore.name}`;

// OnMounted Method
onMounted(async () => {
    try {
        typeChart.value = 'bar';
        if (authStore.idParams.semester != "ALL") {
            const response = await axios.get(`${authStore.url}/grafikAudit/${authStore.idParams.tahun}/${authStore.idParams.semester}`, authStore.header);
            await nextTick();
            prodiDatas.value = response.data;
            renderCharts(prodiDatas.value);
        } else {
            const response = await axios.get(`${authStore.url}/grafikAudit/${authStore.idParams.tahun}/${authStore.idParams.semester}`, authStore.header);
            await nextTick();
            prodiDatas.value = response.data;
            renderChartsALL(prodiDatas.value);
        }
    } catch (error) {
        console.error('Error fetching data:', error);
    }
});

// Change Chart Method
const changeChart = async () => {
    if (authStore.idParams.semester != "ALL") {
        await nextTick();
        renderCharts(prodiDatas.value);
    } else {
        await nextTick();
        renderChartsALL(prodiDatas.value);
    }
}

// Back Method
const goBack = () => {
    router.push({ name: "LaporanAuditAsAdmin" });
};

// Render Charts Method
function renderCharts(datas) {
    // Destroy existing charts before creating new ones
    datas = [datas];
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
        const datasets = [];
        const canvasId = `bar-${index}`;
        const canvasId1 = `spider-${index}`;
        const canvasId2 = `line-${index}`;
        let ctx,ctx1,ctx2;
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
        const datasetsBar = [];
        item.prodi[0].forEach((res,i) => {
            const randomColor = `rgba(${Math.floor(Math.random() * 256)}, ${Math.floor(Math.random() * 256)}, ${Math.floor(Math.random() * 256)}, 0.5)`;
            backgroundColors.push(randomColor);
            borderColors.push(randomColor.replace('0.5', '1'));
            datasetsBar.push({
                label: [item.prodi[0][i]],
                data: [parseFloat(item.poin[0][i])],
                backgroundColor: randomColor,

                borderColor: randomColor.replace('0.5', '1'),
                borderWidth: 1
            });
        });

        datasets.push({
            label: 'Total Nilai Diri',
            data: item.poin[0],
            backgroundColor: backgroundColors,
            borderColor: borderColors,
            fill: true,
            borderWidth: 1
        });

        if (typeChart.value == 'bar') {
            // Bar Chart
            charts.value[canvasId] = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ["CAPAIAN PRODI"],
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
                    labels: item.prodi[0],
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
                    labels: item.prodi[0],
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

// Render All Charts Method
function renderChartsALL(datas) {
    // Destroy existing charts before creating new ones
    datas = [datas];
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
        const datasets = [];
        const canvasId = `bar-${index}`;
        const canvasId1 = `spider-${index}`;
        const canvasId2 = `line-${index}`;

        let ctx,ctx1,ctx2;
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

        item.semester.forEach((sem, i) => {
            const randomColor = `rgba(${Math.floor(Math.random() * 256)}, ${Math.floor(Math.random() * 256)}, ${Math.floor(Math.random() * 256)}, 0.5)`;
            backgroundColors.push(randomColor);
            borderColors.push(randomColor.replace('0.5', '1'));

            datasets.push({
                label: sem,
                data: item.poin[i].map(parseFloat),
                backgroundColor: randomColor,
                borderColor: randomColor.replace('0.5', '1'),
                borderWidth: 1,
                fill: true
            });
        });

        if (typeChart.value == 'bar') {
            charts.value[canvasId] = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: item.prodi[0], // Assumes all semesters have the same labels
                    datasets: datasets
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
        } else if (typeChart.value == 'radar' ) {
            // Radar Chart
            charts.value[canvasId1] = new Chart(ctx1, {
                type: 'radar',
                data: {
                    labels: item.prodi[0], // Assumes all semesters have the same labels
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
                    labels: item.prodi[0], // Assumes all semesters have the same labels
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

const visualStandar = ()=>{
    router.push({name:'DetailVisualStandarAdmin'})
}
</script>
