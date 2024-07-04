<template v-if="authStore.role === 'auditor'">
    <div class="bg-gray-200 min-h-screen p-4">
        <div class="bg-white w-3/4 rounded-xl flex flex-col divide-y-2 shadow-md">
            <!-- Header Start -->
            <div class="p-4 flex items-center justify-between">
                <h1 class="text-2xl font-bold">Fakultas Form</h1>

                <!-- Button Method -->
                <button @click="goBack" class="flex items-center gap-2 bg-neutral-400 hover:bg-neutral-500 shadow-md text-white py-2 px-4 rounded">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
                    </svg>
                    Back
                </button>
            </div>
            <!-- Header Start -->

            <!-- Form Start -->
            <form @submit.prevent="createFakultas" class="py-4 flex flex-col gap-4 divide-y-2">
                <div class="flex flex-col gap-4 px-4">
                    <!-- Fakultas -->
                    <div class="flex flex-col gap-1">
                        <label class="font-medium" for="fakultas">Fakultas</label>
                        <div class="flex items-center divide-x-[1px] divide-black gap-2 pl-2 border border-black rounded bg-neutral-300">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-mortarboard" viewBox="0 0 16 16">
                                <path d="M8.211 2.047a.5.5 0 0 0-.422 0l-7.5 3.5a.5.5 0 0 0 .025.917l7.5 3a.5.5 0 0 0 .372 0L14 7.14V13a1 1 0 0 0-1 1v2h3v-2a1 1 0 0 0-1-1V6.739l.686-.275a.5.5 0 0 0 .025-.917zM8 8.46 1.758 5.965 8 3.052l6.242 2.913z"/>
                                <path d="M4.176 9.032a.5.5 0 0 0-.656.327l-.5 1.7a.5.5 0 0 0 .294.605l4.5 1.8a.5.5 0 0 0 .372 0l4.5-1.8a.5.5 0 0 0 .294-.605l-.5-1.7a.5.5 0 0 0-.656-.327L8 10.466zm-.068 1.873.22-.748 3.496 1.311a.5.5 0 0 0 .352 0l3.496-1.311.22.748L8 12.46z"/>
                            </svg>
                            <input v-model="form.fakultas" type="text" placeholder="Fakultas" id="fakultas" name="fakultas" class="p-2 w-full focus:outline-none">
                        </div>
                    </div>

                    <!-- Prodi -->
                    <div class="flex flex-col gap-1">
                        <label class="font-medium" for="prodi">Prodi</label>
                        <div class="flex items-center divide-x-[1px] divide-black gap-2 pl-2 border border-black rounded bg-neutral-300">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-mortarboard" viewBox="0 0 16 16">
                                <path d="M8.211 2.047a.5.5 0 0 0-.422 0l-7.5 3.5a.5.5 0 0 0 .025.917l7.5 3a.5.5 0 0 0 .372 0L14 7.14V13a1 1 0 0 0-1 1v2h3v-2a1 1 0 0 0-1-1V6.739l.686-.275a.5.5 0 0 0 .025-.917zM8 8.46 1.758 5.965 8 3.052l6.242 2.913z"/>
                                <path d="M4.176 9.032a.5.5 0 0 0-.656.327l-.5 1.7a.5.5 0 0 0 .294.605l4.5 1.8a.5.5 0 0 0 .372 0l4.5-1.8a.5.5 0 0 0 .294-.605l-.5-1.7a.5.5 0 0 0-.656-.327L8 10.466zm-.068 1.873.22-.748 3.496 1.311a.5.5 0 0 0 .352 0l3.496-1.311.22.748L8 12.46z"/>
                            </svg>
                            <input v-model="form.prodi" type="text" placeholder="Prodi" id="prodi" name="prodi" class="p-2 w-full focus:outline-none">
                        </div>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="px-4 pt-4 w-full flex flex-row-reverse">
                    <button type="submit" class="bg-sky-500 text-white py-2 px-4 rounded hover:bg-sky-700 shadow-md">Save</button>
                </div>
            </form>
            <!-- Form End -->
        </div>
    </div>
</template>

<script setup>
// Import Library
import axios from "axios";
import { ref } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "@/router/auth.js";

// Variable Declaration
const authStore = useAuthStore();
const router = useRouter();
const form = ref({
    fakultas: null,
    prodi: null
});

// Create Fakultas Method
const createFakultas = async () => {
    try {
        await axios.post(`${authStore.url}/fakultas`, form.value, authStore.header);

        router.push({ name: 'FakultasListAsAuditor' });
    } catch (e) {
        console.log(e);
    }
}

document.title=`Create Fakultas | ${authStore.name}`;

// Back Method
const goBack = () => {
    router.push({ name: 'FakultasListAsAuditor' });
}
</script>
