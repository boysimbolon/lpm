<template v-if="authStore.role === 'admin' ">
    <div class="bg-gray-200 min-h-screen p-4">
        <div class="bg-white w-3/4 rounded-xl flex flex-col divide-y-2 shadow-md">
            <!-- Header Start -->
            <div class="p-4 flex items-center justify-between">
                <h1 class="text-2xl font-bold">Edit Tipe Standar Form</h1>

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
            <form @submit.prevent="updateTipeAudit" class="py-4 flex flex-col gap-4 divide-y-2">
                <div class="flex flex-col gap-4 px-4">
                    <!-- Tipe Audit -->
                    <div class="flex flex-col gap-1">
                        <label class="font-medium" for="tipe_audit">Tipe Audit</label>
                        <div class="flex items-center divide-x-[1px] divide-black gap-2 pl-2 border border-black rounded bg-neutral-300">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-clipboard" viewBox="0 0 16 16">
                                <path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1z"/>
                                <path d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0z"/>
                            </svg>
                            <input v-model="idParams.idParams.tipe_audit" type="text" placeholder="Tipe Audit" id="tipe_audit" name="tipe_audit" class="p-2 w-full focus:outline-none">
                        </div>
                    </div>

                    <!-- Fakultas - Prodi -->
                    <div class="flex flex-col gap-1">
                        <label class="font-medium" >Fakultas - Prodi</label>
                        <div class="flex items-center divide-x-[1px] divide-black gap-2 pl-2 border border-black rounded bg-neutral-300">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-mortarboard" viewBox="0 0 16 16">
                                <path d="M8.211 2.047a.5.5 0 0 0-.422 0l-7.5 3.5a.5.5 0 0 0 .025.917l7.5 3a.5.5 0 0 0 .372 0L14 7.14V13a1 1 0 0 0-1 1v2h3v-2a1 1 0 0 0-1-1V6.739l.686-.275a.5.5 0 0 0 .025-.917zM8 8.46 1.758 5.965 8 3.052l6.242 2.913z"/>
                                <path d="M4.176 9.032a.5.5 0 0 0-.656.327l-.5 1.7a.5.5 0 0 0 .294.605l4.5 1.8a.5.5 0 0 0 .372 0l4.5-1.8a.5.5 0 0 0 .294-.605l-.5-1.7a.5.5 0 0 0-.656-.327L8 10.466zm-.068 1.873.22-.748 3.496 1.311a.5.5 0 0 0 .352 0l3.496-1.311.22.748L8 12.46z"/>
                            </svg>
                            <select v-model="idParams.idParams.id_fakultas" class="p-2 w-full focus:outline-none">
                                <option v-for="fak in fakultas" :key="fak.id" :value="fak.id">{{fak.fakultas}} - {{fak.prodi}}</option>
                            </select>
                        </div>
                    </div>

                    <!-- Tahun Ajaran -->
                    <div class="flex flex-col gap-1">
                        <label class="font-medium" for="periode">Tahun Ajaran</label>
                        <div class="flex items-center divide-x-[1px] divide-black gap-2 pl-2 border border-black rounded bg-neutral-300">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-calendar-event" viewBox="0 0 16 16">
                                <path d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5z"/>
                                <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z"/>
                            </svg>
                            <select v-model="idParams.idParams.periode" name="periode" id="periode" class="p-2 w-full focus:outline-none">
                                <option v-for="period in periodeOptions" :key="period" :value="period">{{ period }}</option>
                            </select>
                        </div>
                    </div>

                    <!-- Semester -->
                    <div class="flex flex-col gap-1">
                        <label class="font-medium" for="periode">Semester</label>
                        <div class="flex items-center divide-x-[1px] divide-black gap-2 pl-2 border border-black rounded bg-neutral-300">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-calendar-event" viewBox="0 0 16 16">
                                <path d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5z"/>
                                <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z"/>
                            </svg>
                            <select v-model="idParams.idParams.semester" name="periode" id="periode" class="p-2 w-full focus:outline-none">
                                <option value="genap">Genap</option>
                                <option value="ganjil">Ganjil</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="px-4 pt-4 w-full flex justify-end">
                    <button type="submit" class="bg-sky-500 text-white py-2 px-4 rounded shadow-md hover:bg-sky-700">Save</button>
                </div>
            </form>
            <!-- Form End -->
        </div>
    </div>
</template>

<script setup>
// Import Library
import axios from "axios";
import { useRouter } from "vue-router";
import { ref, onMounted } from "vue";
import { useAuthStore } from "@/router/auth.js";

// Variable Declaration
const idParams = useAuthStore();
const router = useRouter();
const authStore = useAuthStore();
const periodeOptions = ref([]);
const fakultas = ref();
document.title = `Edit Tipe Audit | ${authStore.name}`;

// Generate Periode Method
const generatePeriodeOptions = () => {
    const tahun = 2020;
    const periode = [];

    for (let i = 0; i < 10; i++) {
        const startYear = tahun + i;
        const endYear = startYear + 1;
        const periodeText = `${startYear}/${endYear}`;
        periode.push(periodeText);
    }

    return periode;
};

// OnMounted Method
onMounted(async () => {
    if(authStore.role !== 'admin'){
        alert("Password dan Username Tidak Tepat");
        router.push({ name: "login" });
        return; // Tambahkan return agar kode berikutnya tidak dieksekusi
    }

    try {
        const resFak = await axios.get(`${authStore.url}/fakultas`, authStore.header);
        fakultas.value = resFak.data;
    } catch (error) {
        console.error("Error fetching data:", error);
        // Tangani error sesuai kebutuhan, misalnya menampilkan pesan error
    }

    periodeOptions.value = generatePeriodeOptions();
});

// Update Tipe Audit Method
const updateTipeAudit = async () => {
    try {
        await axios.put(`${authStore.url}/tipeAudits/${idParams.idParams.id}`,
            idParams.idParams
        , authStore.header);

        idParams.idParams = null;
        router.push({ name: 'TipeAuditListAsAdmin' });
    } catch (error) {
        if (error.response && error.response.status === 422) {
            formErrors.value = error.response.data.errors;
        } else {
            console.log(error);
        }
    }
};

// Back Method
const goBack = () => {
    router.push({ name: 'TipeAuditListAsAdmin' });
};
</script>
