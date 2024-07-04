<template v-if="authStore.role == 'admin'">
    <div class="bg-gray-200 h-screen p-4">
        <div class="bg-white w-3/4 rounded-xl flex flex-col divide-y-2 shadow-md">
            <!-- Header Start -->
            <div class="p-4 flex items-center justify-between">
                <h1 class="text-2xl font-bold">Tipe Audit Form</h1>

                <!-- Back Button -->
                <button @click="goBack" class="flex items-center gap-2 bg-neutral-400 hover:bg-neutral-500 shadow-md text-white py-2 px-4 rounded">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
                    </svg>
                    Back
                </button>
            </div>
            <!-- Header End -->

            <!-- Form Start -->
            <div  class="py-4 flex flex-col gap-4 divide-y-2">
                <div class="flex flex-col gap-4 px-4">
                    <!-- Tipe Audit -->
                    <div class="flex flex-col gap-1">
                        <label class="font-medium" for="tipe_audit">Tipe Audit</label>
                        <div class="flex items-center divide-x-[1px] divide-black gap-2 pl-2 border border-black rounded bg-neutral-300">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-clipboard" viewBox="0 0 16 16">
                                <path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1z"/>
                                <path d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0z"/>
                            </svg>
                            <input v-model="form.tipe_audit" type="text" placeholder="Tipe Audit" id="tipe_audit" name="tipe_audit" class="p-2 w-full focus:outline-none">
                        </div>
                    </div>

                    <!-- Fakultas - Prodi -->
                    <div class="flex flex-col gap-1">
                        <label class="font-medium">Fakultas - Prodi</label>
                        <div class="flex items-center divide-x-[1px] divide-black gap-2 pl-2 border border-black rounded bg-neutral-300">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-mortarboard" viewBox="0 0 16 16">
                                <path d="M8.211 2.047a.5.5 0 0 0-.422 0l-7.5 3.5a.5.5 0 0 0 .025.917l7.5 3a.5.5 0 0 0 .372 0L14 7.14V13a1 1 0 0 0-1 1v2h3v-2a1 1 0 0 0-1-1V6.739l.686-.275a.5.5 0 0 0 .025-.917zM8 8.46 1.758 5.965 8 3.052l6.242 2.913z"/>
                                <path d="M4.176 9.032a.5.5 0 0 0-.656.327l-.5 1.7a.5.5 0 0 0 .294.605l4.5 1.8a.5.5 0 0 0 .372 0l4.5-1.8a.5.5 0 0 0 .294-.605l-.5-1.7a.5.5 0 0 0-.656-.327L8 10.466zm-.068 1.873.22-.748 3.496 1.311a.5.5 0 0 0 .352 0l3.496-1.311.22.748L8 12.46z"/>
                            </svg>
                            <select v-model="form.id_fakultas" class="p-2 w-full focus:outline-none">
                                <option v-for="fak in Fakultas" :key="fak" :value="fak.id">{{ fak.fakultas }} - {{ fak.prodi }}</option>
                            </select>
                        </div>
                    </div>

                    <!-- Tahun Ajaran -->
                    <div class="flex flex-col gap-1">
                        <label class="font-medium">Tahun Ajar</label>
                        <div class="flex items-center divide-x-[1px] divide-black gap-2 pl-2 border border-black rounded bg-neutral-300">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-calendar-event" viewBox="0 0 16 16">
                                <path d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5z"/>
                                <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z"/>
                            </svg>
                            <select v-model="form.periode" class="p-2 w-full focus:outline-none">
                                <option v-for="period in generatePeriodeOptions" :key="period" :value="period">{{ period }}</option>
                            </select>
                        </div>
                    </div>

                    <!-- Semester -->
                    <div class="flex flex-col gap-1">
                        <label class="font-medium" >Semester</label>
                        <div class="flex items-center divide-x-[1px] divide-black gap-2 pl-2 border border-black rounded bg-neutral-300">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-calendar-event" viewBox="0 0 16 16">
                                <path d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5z"/>
                                <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z"/>
                            </svg>
                            <select v-model="form.semester" class="p-2 w-full focus:outline-none">
                                <option value="genap">Genap</option>
                                <option value="ganjil">Ganjil</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="px-4 pt-4 w-full flex flex-row-reverse gap-4 justify-between">
                    <button type="submit" class="bg-sky-500 text-white py-2 px-4 rounded hover:bg-sky-700 shadow-md" @click="createTipeAudit('save')">Save</button>
                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white py-2 px-4 rounded hover:bg-sky-700 shadow-md" @click="createTipeAudit('salin')">Salin</button>
                </div>
            </div>
            <!-- Form End -->
        </div>
    </div>
</template>

<script setup>
// Import Library
import axios from "axios";
import { computed, ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "@/router/auth.js";

// Variable Declaration
const authStore = useAuthStore();
const router = useRouter();
const form = ref({
    tipe_audit: null,
    periode: null,
    semester: null,
    id_fakultas:null,
    tipe_audit_sebelumnya: null
});
const Fakultas = ref();
document.title = `Create Tipe Audit | ${authStore.name}`;

onMounted(async () => {
    try {
        const resFak = await axios.get(`${authStore.url}/fakultas`, authStore.header);
        Fakultas.value = resFak.data;
    } catch (error) {
        console.error("Error fetching data:", error);
        // Tangani error sesuai kebutuhan, misalnya menampilkan pesan error
    }
});


// Create Tipe Audit Method
const createTipeAudit = async (tipe) => {
    try {
        if(form.value.tipe_audit != null && form.value.periode != null){
            if (tipe === "save") {
                await axios.post(`${authStore.url}/tipeAudits`, form.value,authStore.header);
                router.push({ name: 'TipeAuditListAsAdmin' });
            } else if (tipe === "salin") {
                const options = authStore.idParams.map(tipeAudit => `${tipeAudit.id}) ${tipeAudit.tipe_audit} - ${tipeAudit.periode} - ${tipeAudit.semester} - ${tipeAudit.fakultas.fakultas} - ${tipeAudit.fakultas.prodi}`);
                const selectedOption = prompt(`PILIH TIPE AUDIT yang akan disalin:\n${options.join('\n')}`);
                if (selectedOption != "") {
                    if(confirm("Ingin Menyalin Standar dan Pernyataan dari tipe audit sebelumnya?")){
                        if (selectedOption) {
                            form.value.tipe_audit_sebelumnya = selectedOption;
                            await axios.post(`${authStore.url}/salinAudits`, form.value, authStore.header);
                            router.push({ name: 'TipeAuditListAsAdmin' });
                        } else {
                            console.log("ID Tipe Audit tidak valid.");
                        }
                    } else {
                        console.log("Penyalinan dibatalkan oleh pengguna.");
                    }
                } else {
                    console.log("Pilihan tidak valid.");
                }
            }
        }
    } catch (error) {
        if (error.response) {
            // Server responded with a status other than 2xx
            console.error("Error response:", error.response.data);
        } else if (error.request) {
            // No response received from server
            console.error("Error request:", error.request);
        } else {
            // Other errors
            console.error("Error:", error.message);
        }
    }
};


// Generate Periode Method
const generatePeriodeOptions = computed(() => {
    const tahun = 2020;
    const periode = [];
    for (let i = 0; i < 10; i++) {
        const startYear = tahun + i;
        const endYear = startYear + 1;
        const periodeText = `${startYear}/${endYear}`;
        periode.push(periodeText);
    }
    return periode;
});

// Back Method
const goBack = () => {
    router.push({ name: 'TipeAuditListAsAdmin' });
}
</script>
