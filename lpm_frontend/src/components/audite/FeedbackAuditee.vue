<template v-if="authStore.role === 'auditee'">
    <div class="relative flex bg-gray-200 min-h-screen p-4 gap-5 overflow-hidden">
        <div class="bg-white min-w-[100%] max-w-[100%] h-full rounded-xl flex flex-col divide-y-2 shadow-md transition-all transition-duration-700 ease-in-out" :style="{ minWidth: tableWidth, maxWidth: tableWidth }">
            <!-- Header Start -->
            <div class="p-5">
                <h1 class="text-xl font-bold">FeedBack Auditor</h1>
            </div>
            <!-- Header End -->

            <!-- Content Start -->
            <div id="audit" class="p-5 flex flex-col gap-5">
                <!-- Select Option Start -->
                <div class="flex gap-5 flex-col">
                    <!-- Tipe Audit -->
                    <div class="flex items-center justify-between">
                        <label for="tipeAudit" class="font-semibold mb-2 w-1/2">Pilih Tipe Audit</label>
                        <select v-model="form.audit" id="tipeAudit" required class="border-2 border-[#cccccc] rounded p-1 w-1/2 capitalize" @change="unikstandar()">
                            <option disabled >Pilih Tipe Audit</option>
                            <option v-for="tipe in tipeAudit" :key="tipe.id" :value="tipe.id">{{ tipe.tipe_audit }} - {{ tipe.fakultas.prodi }} - {{ tipe.periode }} - {{ tipe.semester }}</option>
                        </select>
                    </div>

                    <!-- Standar -->
                    <div class="flex items-center justify-between">
                        <label for="tipeStandar" class="font-semibold mb-2 w-1/2">Pilih Standar</label>
                        <select v-model="form.standar" id="tipeStandar" required class="border-2 border-[#cccccc] rounded p-1 w-1/2" @change="createAudit">
                            <option v-for="standar in standars" :key="standar.id" :value="standar.id">{{ standar.jenis_standar }}</option>
                        </select>
                    </div>
                </div>
                <!-- Select Option End -->

                <!-- Table Start -->
                <div class="min-w-full inline-block align-middle h-full flex flex-col gap-5">
                    <div class="flex overflow-auto border-gray-200 rounded-md shadow-md">
                        <table class="w-full relative rounded-md">
                            <thead>
                                <tr class="sticky top-0">
                                    <th class="sticky top-0 px-6 py-2 w-[5%] text-center bg-gray-200">No.</th>
                                    <th class="sticky top-0 px-6 py-2 w-[50%] text-start bg-gray-200">Pernyataan Standar</th>
                                    <th class="sticky top-0 px-6 py-2 w-[15%] text-start bg-gray-200">Dokumen Pendukung</th>
                                    <th class="sticky top-0 px-6 py-2 w-[10%] text-center bg-gray-200">Nilai Diri</th>
                                    <th class="sticky top-0 px-6 py-2 w-[10%] text-center bg-gray-200">Catatan (Optional)</th>
                                    <th class="sticky top-0 px-6 py-2 w-[10%] text-center bg-gray-200">FeedBack</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                <!-- Loop untuk setiap item standar dari database -->
                                <template v-if="pernyataan && pernyataan.length > 0">
                                    <tr class="odd:bg-white even:bg-gray-100 hover:bg-gray-100" v-for="(standar, index) in pernyataan" :key="index">
                                        <td class="px-6 py-2 text-center text-gray-800">{{ index + 1 }}</td>
                                        <td class="px-6 py-2 text-gray-800">{{ standar.per_standars.pernyataan_standar }}</td>
                                        <td class="px-6 py-2 text-gray-800">
                                            <div v-if="standar.dok_pendukung != null" class="text-start text-gray-800">
                                                <div v-for="(file,index) in files(standar.dok_pendukung)" :key="index" style="cursor: pointer; text-decoration: underline blue 2px; text-align: left">
                                                    <span @click="open(file)" v-if="file.type == 'pdf' || file.type == 'jpg' || file.type == 'jpeg' || file.type == 'png' || file.type == 'svg'">{{ index + 1 }}.{{ file.name }}</span>
                                                    <span @click="download(file)" v-else>{{ index + 1 }}.{{ file.name }}</span>
                                                </div>
                                            </div>
                                            <div v-else class="text-red-500">Belum ada file</div>
                                        </td>
                                        <td class="px-6 py-2 text-center">
                                            <span class="rounded">{{ standar.nilai_diri }}/{{ standar.per_standars.poin }}</span>
                                        </td>
                                        <td>
                                            <div class="flex justify-center items-center">
                                                <span class="p-2" v-if="standar.catatan != null">{{ standar.catatan }}</span>
                                                <span class="p-2" v-else>X</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-2">
                                            <span v-if="standar.feedback !== 'null'" class="flex justify-center items-center">{{ standar.feedback }}</span>
                                            <span v-else class="flex justify-center items-center">X</span>
                                        </td>
                                    </tr>
                                </template>
                                <!-- Penanganan jika pernyataan_standar kosong -->
                                <template v-else>
                                    <td colspan="6" class="text-center p-4">Data tidak tersedia</td>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Table End -->
            </div>
        </div>

        <!-- Carousel Start -->
        <div class="absolute hidden bg-gray-200/75 min-w-full min-h-full pr-10" id="carousel">
            <div class="fixed top-0 flex flex-col items-center justify-center h-full max-w-full pr-4">
                <div v-for="(url, index) in pdfUrl" :key="index" class="w-full z-10">
                    <div class="flex flex-col gap-4">
                        <!-- Carousel Header Start -->
                        <div class="flex w-full items-center">
                            <h1 class="text-2xl font-bold mx-auto">{{ url.name }}</h1>
                            <!-- Close Button -->
                            <button @click="keluar" class="py-2 px-4 w-fit rounded bg-red-500 text-white hover:bg-red-700 cursor-pointer shadow-md">Keluar</button>
                        </div>
                        <!-- Carousel Header End -->
                    </div>

                    <!-- Preview File Start -->
                    <div class="flex justify-center mt-4">
                        <iframe v-if="url.type == 'pdf'" :src="url.file" frameborder="0" class="border border-black w-screen max-w-full" style="height: 1000px"></iframe> <!-- Adjusted height for better fit -->
                        <img v-else :src="url.file"  class="border border-black w-screen" style="height: 1000px">
                    </div>
                    <!-- Preview File End -->
                </div>
            </div>
        </div>
        <!-- Carousel End -->
    </div>
</template>

<script setup>
// Import Library
import axios from "axios";
import { onMounted, ref } from "vue";
import { useAuthStore } from "@/router/auth.js";

// Declaration Variable
const authStore = useAuthStore();
const form = ref({
    standar: null,
    audit: null,
    nilaiDiri: null,
    id: authStore.uid
});
document.title = `Feedback Auditor | ${authStore.name}`;
const status = ref();
const standars = ref([]);
const tipeAudit = ref([]);
const pernyataan = ref([]);
const pdfUrl = ref();
const tableWidth = ref('100%');

// Create Audit Method
const createAudit = async () => {
    pernyataan.value = null;
    status.value = false
    if (form.value.standar !== null && form.value.audit !== null) {
        try {
            const response = await axios.get(`${authStore.url}/audits/${form.value.audit}/${form.value.id}/${form.value.standar}`, {
                headers: {
                    Authorization: `Bearer ${authStore.accessToken}`,
                },
            });
            pernyataan.value = response.data.map(item => {
                return {
                    ...item
                };
            });
            const cekstatus = pernyataan.value.find(res => res.progress == 0)
            status.value = cekstatus ? true : false;
        } catch (error) {
            console.error("Error creating audit:", error);
        }
    }
};

// Open File Method
const open = (id) => {
    let carousel = document.getElementById('carousel');
    carousel.classList.add('hidden');
    carousel.classList.remove('block')
    pdfUrl.value = [];
    pdfUrl.value.push({file:`${authStore.fileUrl}/${id.file}`,name:id.name, type:id.type});
    console.log(pdfUrl.value);
    carousel.classList.add('block');
    carousel.classList.remove('hidden')
};

// Files Method
const files = (dokumen) => {
    if (dokumen != null) {
        const Dokumens = JSON.parse(dokumen);
        const modifiedDokumens = Dokumens.map((item) => {
            const splitArray = item.split('/');
            const fullName = splitArray[splitArray.length - 1];
            const nameParts = fullName.split('.');
            const name = nameParts.slice(0, -1).join('.'); // Menggabungkan kembali nama tanpa ekstensi
            const type = nameParts[nameParts.length - 1];
            return {
                file: item,
                name: name,
                type: type
            };
        });
        return modifiedDokumens;
    }
    return []; // Mengembalikan array kosong jika dokumen null
};

// Download Method
const download = async (dokumen) => {
    if (confirm("File ini tidak dapat di preview, anda akan mendownload file tersebut!\n Ingin tetap mendownload?")) {
        try {
            window.location.href = `${authStore.fileUrl}/${dokumen.file}`;
        } catch (error) {
            console.error('File corrupt', error);
        }
    }
};

// Exit File Method
const keluar = () => {
    let carousel = document.getElementById('carousel');

    carousel.classList.add('hidden');
    carousel.classList.remove('block')
};

// OnMounted Method
onMounted(async () => {
    try {
        const resTipe = await axios.get(`${authStore.url}/MyTipeAudits/${form.value.id}`, authStore.header);

        tipeAudit.value = resTipe.data;
    } catch (error) {
        console.error("Error fetching data:", error);
    }
});

// Mapping Standar Method
const unikstandar = async () => {
    if (form.value.audit != null) {
        try {
            const response = await axios.get(`${authStore.url}/my-standars/${form.value.id}/${form.value.audit}`, authStore.header);
            standars.value = response.data;
        } catch (error) {
            console.error("Error fetching data:", error);
            // Anda dapat menambahkan penanganan kesalahan di sini sesuai kebutuhan
        }
    }
};
</script>
