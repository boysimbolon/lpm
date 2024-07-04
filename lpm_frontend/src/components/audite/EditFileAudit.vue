<template v-if="store.role === 'auditee'">
    <div class="relative bg-gray-200 min-h-screen p-4 w-full overflow-hidden">
        <div class="bg-white w-3/4 h-fit rounded-xl flex flex-col divide-y-2 shadow-md">
            <!-- Header Start -->
            <div class="p-4 flex items-center justify-between">
                <h1 class="text-2xl font-bold">Edit Standar Form</h1>

                <!-- Back Button -->
                <button @click="goBack(tipeAudit, jenisStandar)" class="flex items-center gap-2 bg-neutral-400 hover:bg-neutral-500 shadow-md text-white py-2 px-4 rounded">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
                    </svg>
                    Back
                </button>
            </div>
            <!-- Header End -->

            <!-- Form Start -->
            <div class="py-4 flex flex-col gap-4 divide-y-2">
                <div class="flex flex-col gap-4">
                    <!-- Pernyataan Standar -->
                    <div class="flex flex-col gap-1 px-4">
                        <label class="font-medium" for="jenis_standar">Pernyataan Standar</label>
                        <div class="flex items-center divide-x-[1px] divide-black gap-2 pl-2 border border-black rounded bg-neutral-300">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-file-earmark" viewBox="0 0 16 16">
                                <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5z"/>
                            </svg>
                            <h1 class="p-2 w-full focus:outline-none">{{ param.per_standars.pernyataan_standar }}</h1>
                        </div>
                    </div>

                    <!-- Catatan -->
                    <div class="flex flex-col gap-1 px-4">
                        <label class="font-medium" for="jenis_standar">Edit Catatan</label>
                        <div class="flex items-center divide-x-[1px] divide-black gap-2 pl-2 border border-black rounded bg-neutral-300">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-sticky" viewBox="0 0 16 16">
                                <path d="M2.5 1A1.5 1.5 0 0 0 1 2.5v11A1.5 1.5 0 0 0 2.5 15h6.086a1.5 1.5 0 0 0 1.06-.44l4.915-4.914A1.5 1.5 0 0 0 15 8.586V2.5A1.5 1.5 0 0 0 13.5 1zM2 2.5a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 .5.5V8H9.5A1.5 1.5 0 0 0 8 9.5V14H2.5a.5.5 0 0 1-.5-.5zm7 11.293V9.5a.5.5 0 0 1 .5-.5h4.293z"/>
                            </svg>
                            <input type="text" class="p-2 w-full focus:outline-none" v-model="param.catatan">
                        </div>
                    </div>

                    <!-- File -->
                    <div class="flex flex-col gap-1 px-4">
                        <label class="font-medium" for="file">Hapus File Yang tidak diperlukan</label>
                        <span class="text-red-500">File Sebelumnya</span>
                        <div>
                            <div v-for="(file, fileIndex) in dokPendukungArray" :key="fileIndex" class="flex items-center gap-2">
                                <input type="checkbox" v-model="selectedFiles" :value="fileIndex">
                                <span class="underline text-blue-600 hover:cursor-pointer" @click="Preview(`${store.fileUrl}/${file.file}`,file.type)" v-if="file.type == 'pdf' || file.type == 'jpg' || file.type == 'jpeg' || file.type == 'png' || file.type == 'svg'">{{ fileIndex + 1 }}.{{ file.name }}</span>
                                <span class="underline text-blue-600 hover:cursor-pointer" @click="download(`${store.fileUrl}/${file.file}`)" v-else>{{ fileIndex + 1 }}.{{ file.name }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-4 pt-4 w-full flex justify-end">
                    <button class="bg-sky-500 text-white py-2 px-4 rounded shadow-md hover:bg-sky-700" @click="save" v-if="load">Save</button>
                </div>
            </div>
            <!-- Form End -->
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
import { useAuthStore } from "@/router/auth.js";
import axios from "axios";
import { ref,onMounted } from 'vue';
import router from "@/router/index.js";

const store = useAuthStore();
const param = store.idParams.standar;
const tipeAudit = store.idParams.tipeAudit;
const jenisStandar = store.idParams.jenisStandar;
const dokPendukungArray = ref();
const pdfUrl = ref();
const selectedFiles = ref([]);
const load = ref(true);
document.title = `Edit Audit | ${authStore.name}`;

// Save Method
const save = async () => {
    load.value = false
    try {
        if(param.catatan != null || param.catatan != ''){
            axios.post(`${store.url}/audits/${param.id}`, {
                pernyataanPendukung:param.catatan
            },store.header)
                .then(response => {
                    store.scope(param);
                    console.log('Audit updated successfully', response);

                    const data = {
                        tipeAuditParam: tipeAudit,
                        jenisStandarParam: jenisStandar
                    }
                    store.scope(data);
                    router.push({ name: 'IsiDokumenAudit' });
                })
        }
        // Mengurutkan selectedFiles.value dalam urutan menurun
        if(selectedFiles.value.length > 0){
            const sortedFiles = [...selectedFiles.value].sort((a, b) => b - a);
            for (let i = 0; i < sortedFiles.length; i++) {
                await axios.delete(`${store.url}/remove/${param.id}/${sortedFiles[i]}`,store.header);
            }
        }
    } catch (error) {
        console.error('Error:', error);
    }
    load.value = true;
};

// OnMounted Method
onMounted(() => {
    const parsedDokumen = JSON.parse(param.dok_pendukung);
    console.log(param);
    dokPendukungArray.value = parsedDokumen.map((item) => {
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
});

// Download Method
const download = async (dokumen) => {
    if (confirm("File ini tidak dapat di preview, anda akan mendownload file tersebut!\n Ingin tetap mendownload?")) {
        try {
            window.location.href = dokumen;
        } catch (error) {
            console.error('File corrupt', error);
        }
    }
};

// Preview Method
const Preview = (url,preview) => {
    let carousel = document.getElementById('carousel');
    pdfUrl.value = [];
    pdfUrl.value.push({file:url,type:preview});
    carousel.classList.remove('hidden')
    carousel.classList.add('block');
}

// Exit Method
const keluar = () => {
    let carousel = document.getElementById('carousel');

    carousel.classList.add('hidden');
    carousel.classList.remove('block');
}

// Back Method
const goBack = (tipeAudit, jenisStandar) => {
    const data = {
        tipeAuditParam: tipeAudit,
        jenisStandarParam: jenisStandar
    }
    store.scope(data);
    router.push({ name: 'IsiDokumenAudit' });
};
</script>
