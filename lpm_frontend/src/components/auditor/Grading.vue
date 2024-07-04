<template v-if="authStore.role === 'auditor'">
    <div class="flex bg-gray-200 min-h-screen p-4 gap-5">
        <div class="relative bg-white min-w-[100%] max-w-[100%] h-fit rounded-xl flex flex-col divide-y-2 shadow-md transition-all transition-duration-700 ease-in-out">
            <!-- Header Start -->
            <div class="p-5">
                <h1 class="text-2xl font-bold">Submit Nilai Diri</h1>
            </div>
            <!-- Header End -->

            <!-- Content Start -->
            <div id="audit" class="p-5 flex flex-col gap-5">
                <!-- Select Option Start -->
                <div class="flex flex-col gap-5">
                    <!-- Tipe Audit -->
                    <div class="flex items-center justify-between">
                        <label for="tipeAudit" class="font-semibold w-1/2">Pilih Tipe Audit</label>
                        <select v-model="form.audit" id="tipeAudit" required  class="border-2 border-[#cccccc] rounded p-1 w-1/2 capitalize" @change="unikstandar(form.audit)">
                            <option disabled >Pilih Tipe Audit</option>
                            <option v-for="tipe in tipeAudit" :key="tipe.id" :value="tipe.id">{{ tipe.tipe_audit }} - {{tipe.fakultas.prodi}} - {{ tipe.periode }} - {{tipe.semester}}</option>
                        </select>
                    </div>

                    <!-- Standar -->
                    <div class="flex items-center justify-between">
                        <label for="tipeStandar" class="font-semibold w-1/2">Pilih Standar</label>
                        <select v-model="form.standar" id="tipeStandar" required class="border-2 border-[#cccccc] rounded p-1 w-1/2" @change="createAudit()">
                            <option v-for="standar in unikstandar(form.audit)" :key="standar.standars.id" :value="standar.standars.id">{{ standar.standars.jenis_standar }}</option>
                        </select>
                    </div>
                </div>
                <!-- Select Option End -->

                <!-- Table Start -->
                <div class="min-w-full inline-block align-middle h-full flex flex-col gap-5">
                    <div class="flex overflow-auto border-gray-200 rounded-md shadow-md">
                        <table class="w-full rounded-md">
                            <thead>
                                <tr class="sticky top-0">
                                    <th class="px-6 py-2 w-[5%] text-center bg-gray-200">No.</th>
                                    <th class="px-6 py-2 w-[45%] text-start bg-gray-200">Pernyataan Standar</th>
                                    <th class="px-6 py-2 w-[15%] text-start bg-gray-200">Dokumen Pendukung</th>
                                    <th class="px-6 py-2 w-[10%] text-center bg-gray-200">Nilai Diri</th>
                                    <th class="px-6 py-2 w-[10%] text-center bg-gray-200">Nilai Maksimum</th>
                                    <th class="px-6 py-2 w-[10%] text-center bg-gray-200">FeedBack</th>
                                    <th class="px-6 py-2 w-[5%] text-center bg-gray-200">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                <!-- Loop untuk setiap item standar dari database -->
                                <tr class="odd:bg-white even:bg-gray-100 hover:bg-gray-100" v-for="(standar, index) in pernyataan" :key="index">
                                    <template v-if="standar.per_standars.pernyataan_standar != null">
                                        <td class="px-6 py-2 w-[5%] text-center text-gray-800">{{ index + 1 }}</td>
                                        <td class="px-6 py-2 w-[45%] text-start text-gray-800">
                                            <span>{{ standar.per_standars.pernyataan_standar }}</span><br>
                                            <span v-if="standar.catatan != null" class="text-red-400">*{{ standar.catatan }}</span>
                                        </td>
                                        <td class="px-6 py-2 w-[15%] text-start text-gray-800">
                                            <!-- Input file untuk unggah dokumen pendukung -->
                                            <div v-if="standar.dok_pendukung == null">
                                                <span class="text-red-500">Belum Ada file</span>
                                            </div>
                                            <!-- Pesan jika dokumen sudah diunggah   -->
                                            <div v-for="(file,index) in files(standar.dok_pendukung)" :key="index" style="cursor: pointer; text-decoration: underline blue 2px; text-align: left">
                                                <span @click="open(file)" v-if="file.type == 'pdf' || file.type == 'jpg' || file.type == 'jpeg' || file.type == 'png' || file.type == 'svg'">{{ index + 1 }}.{{ file.name }}</span>
                                                <span @click="download(file)" v-else>{{ index + 1 }}.{{ file.name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-2 w-[10%] text-center text-gray-800">
                                            <!-- Input nilai diri jika dokumen belum diunggah -->
                                            <div v-if="standar.dok_pendukung != null">
                                                <input type="number" min="1" v-model="standar.nilaiDiri"  v-if="standar.nilai_diri == '0'" class="border-2 border-[#cccccc] rounded px-1 text-center">
                                                <span v-else>{{ standar.nilai_diri }}</span>
                                            </div>
                                            <span v-else class="text-red-500">X</span>
                                        </td>
                                        <td class="px-6 py-2 w-[10%] text-center text-gray-800">{{ standar.per_standars.poin }}</td>
                                        <td class="px-6 py-2 w-[10%] text-center text-gray-800">
                                            <textarea class="p-2 border-2 border-solid border-black rounded" v-model="standar.feedback" v-if="standar.dok_pendukung != null && standar.progress == 0 && standar.feedbackStatus"></textarea>
                                            <span v-else-if="standar.feedback != 'null'">{{ standar.feedback }}</span>
                                            <span v-else>-</span>
                                        </td>
                                        <td class="px-6 py-2 text-center text-gray-800">
                                            <!-- Tombol Save untuk menyimpan jika dokumen belum diunggah -->
                                            <div v-if="standar.nilai_diri === '0' && standar.dok_pendukung != null && standar.feedbackStatus" @click="kirim(standar.id)" class="bg-blue-500 py-2 px-4 cursor-pointer rounded flex items-center justify-center text-white">
                                                <svg class="animate-spin" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg" width="18" height="18" v-if="load">
                                                    <path d="M32 3C35.8083 3 39.5794 3.75011 43.0978 5.20749C46.6163 6.66488 49.8132 8.80101 52.5061 11.4939C55.199 14.1868 57.3351 17.3837 58.7925 20.9022C60.2499 24.4206 61 28.1917 61 32C61 35.8083 60.2499 39.5794 58.7925 43.0978C57.3351 46.6163 55.199 49.8132 52.5061 52.5061C49.8132 55.199 46.6163 57.3351 43.0978 58.7925C39.5794 60.2499 35.8083 61 32 61C28.1917 61 24.4206 60.2499 20.9022 58.7925C17.3837 57.3351 14.1868 55.199 11.4939 52.5061C8.801 49.8132 6.66487 46.6163 5.20749 43.0978C3.7501 39.5794 3 35.8083 3 32C3 28.1917 3.75011 24.4206 5.2075 20.9022C6.66489 17.3837 8.80101 14.1868 11.4939 11.4939C14.1868 8.80099 17.3838 6.66487 20.9022 5.20749C24.4206 3.7501 28.1917 3 32 3L32 3Z" stroke="white" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    <path d="M32 3C36.5778 3 41.0906 4.08374 45.1692 6.16256C49.2477 8.24138 52.7762 11.2562 55.466 14.9605C58.1558 18.6647 59.9304 22.9531 60.6448 27.4748C61.3591 31.9965 60.9928 36.6232 59.5759 40.9762" stroke="white" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                </svg>
                                                <span v-if="!load">Save</span>
                                            </div>
                                            <!-- Pesan 'Berhasil' jika dokumen sudah diunggah -->
                                            <div v-else class="text-white text-center cursor-pointer">
                                                <button @click="Edit(standar, form.audit, form.standar)" v-if="standar.progress != '1' && standar.dok_pendukung != null" class="py-2 px-4 bg-green-500 hover:bg-green-700 rounded">Edit</button>
                                                <div v-else-if="standar.progress == '1' && standar.dok_pendukung != null" class="bg-green-500 py-2 px-4 hover:bg-green-700 rounded">Selesai</div>
                                                <div v-else class="text-red-500 py-2 px-4">X</div>
                                            </div>
                                        </td>
                                    </template>
                                    <!-- Penanganan jika pernyataan_standar kosong -->
                                    <template v-else>
                                        <td colspan="7" style="text-align: center;">Data tidak tersedia</td>
                                    </template>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Lock Button -->
                    <div class="justify-end flex text-white" v-if="status">
                        <button class="py-2 px-4 bg-blue-500 rounded text-center cursor-pointer shadow-md hover:bg-blue-700" @click="savePermanent">Kunci Audit</button>
                    </div>
                </div>
                <!-- Table End -->
            </div>
            <!-- Content End -->

            <!-- Carousel Start -->
            <div class="absolute hidden bg-gray-200/75 min-w-full min-h-full pr-10" id="carousel">
                <div class="fixed top-0 flex flex-col items-center justify-center h-full max-w-full pr-4">
                    <div v-for="(url, index) in pdfUrl" :key="index" class="pt-4 w-full h-full">
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
    </div>
</template>

<script setup>
// Import Library
import axios from "axios";
import { onMounted, ref } from "vue";
import { useAuthStore } from "@/router/auth.js";
import router from "@/router/index.js";

// Variable Declaration
const store = useAuthStore();
const authStore = useAuthStore();
const form = ref({
    standar: null,
    audit: null,
    nilaiDiri: null,
    id: null
});
document.title = `Nilai Diri | ${authStore.name}`;
const status = ref();
const submit = ref([]);
const standars = ref([]);
const tipeAudit = ref([]);
const pernyataan = ref([]);
const load = ref(false);
const pdfUrl = ref();

// Create Audit Method
const createAudit = async () => {
    pernyataan.value = null;
    status.value = false;

    if (form.value.standar !== null && form.value.audit !== null) {
        try {
            const response = await axios.get(`${authStore.url}/audits/${form.value.audit}/${form.value.standar}`,
                authStore.header);

            pernyataan.value = response.data.map(item => {
                const cek = item.feedback === null;
                const nilai = item.nilai_diri != '0'
                return {
                    ...item,
                    nilaiDiri:nilai?item.nilai_diri:0,
                    feedbackStatus: cek
                };
            });

            pernyataan.value.forEach(res => {
                if (res.progress == 0) {
                    status.value = true;
                }
            });
        } catch (error) {
            console.error("Error creating audit:", error);
        }
    } else {
        console.warn("Standar atau audit tidak boleh null.");
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
            window.location.href = `${store.fileUrl}/${dokumen.file}`;
        } catch (error) {
            console.error('File corrupt', error);
        }
    }
};

// Exit Method
const keluar = () => {
    let carousel = document.getElementById('carousel');
    carousel.classList.add('hidden');
    carousel.classList.remove('block')
}

// Send Method
const kirim = (id) => {
    load.value = true;
    const pernyataanDitemukan = pernyataan.value.find((item) => item.id === id);

    if (pernyataanDitemukan) {
        submit.value = pernyataanDitemukan;
    } else {
        console.log(`Pernyataan dengan ID ${id} tidak ditemukan.`);
        return;
    }
    let data = new FormData();
    if(submit.value.nilaiDiri != 0){
        data.append('nilai_diri', submit.value.nilaiDiri);
    }
    if(submit.value.nilaiDiri != null){
        data.append('nilai_diri', submit.value.nilaiDiri);
    }
    data.append('feedback',submit.value.feedback)
    axios.post(`${authStore.url}/audits/${id}`, data, authStore.header)
        .then(response => {
            load.value = false;
            createAudit();
        })
        .catch(error => {
            load.value = false;
            console.error('Error updating audit', error);
        });
};

// OnMounted Method
onMounted(async () => {
    try {
        if (authStore.idParams !== null && authStore.idParams !== undefined) {
            if (authStore.idParams?.tipeAuditParam !== null && authStore.idParams?.standarParam !== null) {
                form.value.audit = authStore.idParams.tipeAuditParam;
                form.value.standar = authStore.idParams.standarParam;
                unikstandar(form.value.audit);
                createAudit();
                authStore.idParams.tipeAuditParam = null;
                authStore.idParams.standarParam = null;
            } else {
                form.value.audit = null;
                form.value.standar = null;
            }
        }

        const [resTipe,resStandar] = await Promise.all([
            axios.get(`${authStore.url}/myTipeAudit/${authStore.uid}`, authStore.header),
            axios.get(`${authStore.url}/perstandars/${authStore.uid}`, authStore.header),
        ]);

        tipeAudit.value = resTipe.data;
        standars.value = resStandar.data;
    } catch (error) {
        console.error("Error fetching data:", error);
    }
});

// Unique Standar
const unikstandar = (id) => {
    const seen = new Map();
    let data = [];

    // Konversi standars.value ke dalam bentuk array jika diperlukan dan filter berdasarkan id_tipe_audits
    data = standars.value.filter(res => res.id_tipe_audits == id);

    return data.filter(standar => {
        const duplicate = seen.has(standar.id_standars);

        if (!duplicate) {
            seen.set(standar.id_standars, true);
        }

        return !duplicate;
    });
};

// Save Permanently Method
const savePermanent = () => {
    let isError = false; // Variabel untuk melacak kesalahan

    pernyataan.value.forEach((res) => {
        if (res.nilai_diri == 0) {
            isError = true; // Set isError menjadi true jika ada nilai yang kosong
        }
    });

    if (isError) {
        alert('Masih Perlu mengisi Nilai Diri dengan lengkap.');
    } else {
        if(confirm('Yakin ingin Mengunci Audit ini?'))
            // Lakukan permintaan PUT hanya jika tidak ada nilai yang kosong
            axios.put(`${authStore.url}/audits/${form.value.audit}/${form.value.standar}`, {
                progress: 1
            },authStore.header)
                .then(response => {
                    createAudit();
                })
                .catch(error => {
                    console.error('Error:', error);
                });
    }
}

// Edit Method
const Edit = (standar, tipeAuditParam, standarParam) => {
    const data = {
        standar: standar,
        tipeAuditParam: tipeAuditParam,
        standarParam: standarParam
    }
    authStore.scope(data);
    router.push({ name: 'EditScoreAsAuditor' });
}
</script>
