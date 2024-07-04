<template v-if="authStore.role === 'auditee'">
    <div class="relative flex bg-gray-200 min-h-screen p-4 gap-5 w-full overflow-hidden">
        <div class="bg-white min-w-[100%] max-w-[100%] h-full rounded-xl flex flex-col divide-y-2 shadow-md transition-all transition-duration-700 ease-in-out">
            <!-- Header Start -->
            <div class="p-5">
                <h1 class="text-xl font-bold">Upload Dokumen Audit</h1>
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
                                    <th class="sticky top-0 px-6 py-3 w-[5%] text-center bg-gray-200">No.</th>
                                    <th class="sticky top-0 px-6 py-3 w-[50%] text-start bg-gray-200">Pernyataan Standar</th>
                                    <th class="sticky top-0 px-6 py-3 w-[20%] text-start bg-gray-200">Unggah Dokumen Pendukung</th>
                                    <th class="sticky top-0 px-6 py-3 w-[15%] text-center bg-gray-200">Catatan (Optional)</th>
                                    <th class="sticky top-0 px-6 py-3 w-[10%] text-center bg-gray-200">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                <!-- Loop untuk setiap item standar dari database -->
                                <template v-if="pernyataan && pernyataan.length>0">
                                    <tr class="odd:bg-white even:bg-gray-100 hover:bg-gray-100" v-for="(standar, index) in pernyataan" :key="index">
                                        <td class="px-6 py-4 w-[5%] text-center text-gray-800">{{ index + 1 }}</td>
                                        <td class="px-6 py-4 w-[50%] text-gray-800">{{ standar.per_standars.pernyataan_standar }}</td>
                                        <td class="px-6 py-4 w-[20%] text-gray-800">
                                            <!-- Input file untuk unggah dokumen pendukung -->
                                            <div v-if="standar.dok_pendukung === null">
                                                <label :for="'file'+standar.id" class="flex items-center gap-1 hover:cursor-pointer hover:text-blue-400" @click="addFiles(standar.id)" >
                                                    <span class="gap-1 flex items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cloud-arrow-up-fill" viewBox="0 0 16 16">
                                                            <path d="M8 2a5.53 5.53 0 0 0-3.594 1.342c-.766.66-1.321 1.52-1.464 2.383C1.266 6.095 0 7.555 0 9.318 0 11.366 1.708 13 3.781 13h8.906C14.502 13 16 11.57 16 9.773c0-1.636-1.242-2.969-2.834-3.194C12.923 3.999 10.69 2 8 2m2.354 5.146a.5.5 0 0 1-.708.708L8.5 6.707V10.5a.5.5 0 0 1-1 0V6.707L6.354 7.854a.5.5 0 1 1-.708-.708l2-2a.5.5 0 0 1 .708 0z"/>
                                                        </svg>
                                                        Masukan File
                                                    </span>
                                                </label>
                                                <!--                                                <input type="file" @change="handleFiles($event, standar.id)" multiple :name="'file'+standar.id" :id="'file'+standar.id" class="hidden" accept="application/pdf">-->
                                                <div v-if="standar.Url && standar.Url.length > 0">
                                                    <div v-for="(file, fileIndex) in standar.Url" :key="fileIndex" class="flex items-center gap-2 mt-2">
                                                        <span class="underline text-blue-600 hover:cursor-pointer" @click="Preview(file.url)">{{ fileIndex + 1 }}.{{ file.name }}</span>
                                                        <button @click="removeFile(standar.id, fileIndex)" class="text-red-500 hover:text-black">x</button>
                                                        <button @click="renameFile(standar.id, fileIndex)" class="text-blue-500 hover:text-black">rename</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Pesan jika dokumen sudah diunggah   -->
                                            <div v-else >
                                                <div v-for="(file,index) in files(standar.dok_pendukung)" :key="index" style="cursor: pointer; text-decoration: underline blue 2px; text-align: left" v-if="standar.dok_pendukung != null">
                                                    <span @click="open(file)" v-if="file.type == 'pdf' || file.type == 'jpg' || file.type == 'jpeg' || file.type == 'png' || file.type == 'svg'">{{ index + 1 }}.{{ file.name }}</span>
                                                    <span @click="download(file)" v-else>{{ index + 1 }}.{{ file.name }}</span>
                                                </div>
                                                <div v-else class="text-red-400">Belum ada file</div>
                                                <div v-if="standar.progress == 0">
                                                    <div v-if="standar.Url && standar.Url.length > 0">
                                                        <div v-for="(file, fileIndex) in standar.Url" :key="fileIndex" class="flex items-center gap-2 mt-2">
                                                            <span  class="underline text-blue-600 hover:cursor-pointer" @click="Preview(file.url)">{{ fileIndex + 1 }}. {{ file.name }}</span>
                                                            <div class="flex flex-col gap-2 ml-auto">
                                                                <button @click="removeFile(standar.id, fileIndex)" class="text-red-500 hover:text-black">x</button>
                                                                <button @click="renameFile(standar.id, fileIndex)" class="text-blue-500 hover:text-black">rename</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <label :for="'AddFile'+standar.id" class="flex items-center mt-3 hover:cursor-pointer hover:text-blue-400" @click="addFiles(standar.id)">
                                                        <span class="gap-1 flex items-center">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cloud-arrow-up-fill" viewBox="0 0 16 16">
                                                                <path d="M8 2a5.53 5.53 0 0 0-3.594 1.342c-.766.66-1.321 1.52-1.464 2.383C1.266 6.095 0 7.555 0 9.318 0 11.366 1.708 13 3.781 13h8.906C14.502 13 16 11.57 16 9.773c0-1.636-1.242-2.969-2.834-3.194C12.923 3.999 10.69 2 8 2m2.354 5.146a.5.5 0 0 1-.708.708L8.5 6.707V10.5a.5.5 0 0 1-1 0V6.707L6.354 7.854a.5.5 0 1 1-.708-.708l2-2a.5.5 0 0 1 .708 0z"/>
                                                            </svg>
                                                            Tambahkan File
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                        <td class=" w-[15%]">
                                            <div class="flex justify-center items-center">
                                                <textarea class="p-2 border-2 border-solid border-black rounded" v-if="standar.dok_pendukung == null " v-model="standar.catatan"></textarea>
                                                <span class="py-2 px-4 rounded" v-else-if="standar.catatan != null">{{ standar.catatan }}</span>
                                                <span class="py-2 px-4 rounded" v-else>-</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <!-- Tombol Save untuk menyimpan jika dokumen belum diunggah -->
                                            <div v-if="status" class="flex gap-2 justify-center">
                                                <div class="bg-blue-500 py-2 px-4 cursor-pointer rounded-md text-center text-white justify-center hover:bg-blue-700" v-if="standar.Url && standar.Url.length > 0">
                                                    <svg class="animate-spin" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg" width="18" height="18" v-if="load">
                                                        <path d="M32 3C35.8083 3 39.5794 3.75011 43.0978fakultas 5.20749C46.6163 6.66488 49.8132 8.80101 52.5061 11.4939C55.199 14.1868 57.3351 17.3837 58.7925 20.9022C60.2499 24.4206 61 28.1917 61 32C61 35.8083 60.2499 39.5794 58.7925 43.0978C57.3351 46.6163 55.199 49.8132 52.5061 52.5061C49.8132 55.199 46.6163 57.3351 43.0978 58.7925C39.5794 60.2499 35.8083 61 32 61C28.1917 61 24.4206 60.2499 20.9022 58.7925C17.3837 57.3351 14.1868 55.199 11.4939 52.5061C8.801 49.8132 6.66487 46.6163 5.20749 43.0978C3.7501 39.5794 3 35.8083 3 32C3 28.1917 3.75011 24.4206 5.2075 20.9022C6.66489 17.3837 8.80101 14.1868 11.4939 11.4939C14.1868 8.80099 17.3838 6.66487 20.9022 5.20749C24.4206 3.7501 28.1917 3 32 3L32 3Z" stroke="currentColor" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        <path d="M32 3C36.5778 3 41.0906 4.08374 45.1692 6.16256C49.2477 8.24138 52.7762 11.2562 55.466 14.9605C58.1558 18.6647 59.9304 22.9531 60.6448 27.4748C61.3591 31.9965 60.9928 36.6232 59.5759 40.9762" stroke="white" stroke-width="5" stroke-linecap="round" stroke-linejoin="round" class="text-gray-900"></path>
                                                    </svg>
                                                    <button v-if="!load" @click="kirim(standar.id)">Save</button>
                                                </div>
                                                <!-- Pesan 'Berhasil' jika dokumen sudah diunggah -->
                                                <div class="bg-green-500 py-2 px-4 cursor-pointer rounded-md text-center justify-center text-white hover:bg-green-700" v-if="standar.dok_pendukung !=null">
                                                    <button @click="Edit(standar, form.audit, form.standar)">Edit</button>
                                                </div>
                                            </div>
                                            <div v-else class="bg-green-500 text-white rounded-[5px] text-center py-2 px-4">Berhasil</div>
                                        </td>
                                    </tr>
                                </template>
                                <!-- Penanganan jika pernyataan_standar kosong -->
                                <template v-else>
                                    <td colspan="5" class="text-center p-4">Data tidak tersedia</td>
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
</template>

<script setup>
// Import Library
import axios from "axios";
import {onMounted, ref} from "vue";
import {useAuthStore} from "@/router/auth.js";
import router from "@/router/index.js";

// Declaration Variable
const authStore = useAuthStore();
const form = ref({
    standar: null,
    audit: null,
    nilaiDiri: null,
    id: authStore.uid
});
document.title=`Isi Audit | ${authStore.name}`;
const status = ref();
const standars = ref([]);
const tipeAudit = ref([]);
const pernyataan = ref([]);
const load = ref(false);
const pdfUrl = ref();

// Create Audit Method
const createAudit = async () => {
    pernyataan.value = null;
    status.value = false
    if (form.value.standar !== null && form.value.audit !== null) {
        try {
            const response = await axios.get(`${authStore.url}/audits/${form.value.audit}/${form.value.id}/${form.value.standar}`,
             authStore.header);
            pernyataan.value = response.data.map(item => {
                return {
                    ...item,
                    Dokumen: [],
                    Url:[]
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

// Preview File Method
const Preview = (url) => {
    let carousel = document.getElementById('carousel');

    carousel.classList.add('hidden');
    carousel.classList.remove('block');
    pdfUrl.value = [];
    pdfUrl.value.push({ file: url });
    carousel.classList.add('block');
    carousel.classList.remove('hidden');
};

// Handle Files Method
const handleFiles = (event, id, fileName) => {
    const files = Array.from(event.target.files); // Mengambil semua file yang dipilih
    if (files.length > 0) {
        const dokumen = pernyataan.value.find(res => res.id == id);
        if (dokumen) {
            files.forEach(file => {
                dokumen.Dokumen.push({ File: file, name: fileName });
                dokumen.Url.push({
                    url: URL.createObjectURL(file),
                    name: fileName
                });
            });
        }
    } else {
        console.error('Tidak ada file yang dipilih');
    }
};

// Add Files Method
const addFiles = (id) => {
    const fileName = prompt("Masukkan Judul file:");
    // Cek apakah pengguna memberikan nama file atau tidak
    if (fileName) {
        // Buat sebuah elemen <input> untuk memilih file
        const input = document.createElement("input");
        input.type = "file";
        input.style.display = "none"; // Sembunyikan input file
        input.multiple = true;
        // Tambahkan event listener untuk ketika pengguna memilih file
        input.addEventListener("change", (event) => handleFiles(event, id, fileName));
        // Tambahkan input ke dalam dokumen
        document.body.appendChild(input);
        // Klik pada elemen input untuk memunculkan dialog file system
        input.click();
    } else {
        console.log("Tidak ada nama file yang diberikan.");
    }
};

// Send Method
const kirim = (id) => {
    load.value = true;
    // Cari pernyataan dengan ID yang sesuai
    const pernyataanDitemukan = pernyataan.value.find((item) => item.id === id);

    if (!pernyataanDitemukan) {
        console.log(`Pernyataan dengan ID ${id} tidak ditemukan.`);
        load.value = false;
        return;
    }

    // Buat objek FormData
    let data = new FormData();

    // Loop melalui pernyataan untuk menambahkan file yang sesuai
    for (let i = 0; i < pernyataan.value.length; i++) {
        if (pernyataan.value[i].id === id && pernyataan.value[i].Dokumen != null) {
            if(pernyataan.value[i].catatan != null){
                data.append('pernyataanPendukung',pernyataan.value[i].catatan)
            }
            pernyataan.value[i].Dokumen.forEach(file => {
                data.append('dok_pendukung', file.File);
                data.append('name', file.name);
                data.append('judul', pernyataanDitemukan.per_standars.pernyataan_standar);

                axios.post(`${authStore.url}/audits/${id}`, data,authStore.Uploud)
                    .then(response => {
                        console.log('Audit updated successfully', response);
                        load.value = false;
                        createAudit();
                    })
                    .catch(error => {
                        console.error('Error updating audit', error);
                    });
            });
            break;
        }
    }
};

// Remove File Method
const removeFile = (id,fileIndex) => {
    pdfUrl.value = [];
    for (let i = 0; i < pernyataan.value.length; i++) {
        if (pernyataan.value[i].id == id) {
            pernyataan.value[i].Dokumen.splice(fileIndex, 1);
            pernyataan.value[i].Url.splice(fileIndex, 1);
            break;
        }
    }
}

// Rename File Method
const renameFile = (id, fileIndex) => {
    const pernyataanDitemukan = pernyataan.value.find((item) => item.id == id);

    if (pernyataanDitemukan) {
        const newName = prompt('Masukkan nama baru untuk file:', pernyataanDitemukan.Url[fileIndex].name);
        if (newName) {
            pernyataanDitemukan.Url[fileIndex].name = newName;
        }
    } else {
        console.log(`Pernyataan dengan ID ${id} tidak ditemukan.`);
    }
}

// OnMounted Method
onMounted(async () => {
    try {
        if (authStore.idParams !== null && authStore.idParams !== undefined) {
            if (authStore.idParams?.tipeAuditParam !== null && authStore.idParams?.jenisStandarParam !== null) {
                form.value.audit = authStore.idParams.tipeAuditParam;
                form.value.standar = authStore.idParams.jenisStandarParam;
                unikstandar();
                createAudit();
                authStore.idParams.tipeAuditParam = null;
                authStore.idParams.jenisStandarParam = null;
            } else {
                form.value.audit = null;
                form.value.standar = null;
            }
        }

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


// Edit Method
const Edit = (standar, tipeAudit, jenisStandar) => {
    const data = {
        standar: standar,
        tipeAudit: tipeAudit,
        jenisStandar: jenisStandar
    }
    authStore.scope(data);
    router.push({ name: 'EditFile' });
}
</script>
