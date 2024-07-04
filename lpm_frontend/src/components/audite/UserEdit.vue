<template>
    <div class="bg-gray-200 h-screen p-4">
        <div class="bg-white w-3/4 rounded-xl flex flex-col divide-y-2 shadow-md">
            <!-- Header Start -->
            <div class="p-4 flex items-center justify-between">
                <h1 class="text-2xl font-bold">Edit User Form</h1>
            </div>
            <!-- Header End -->

            <!-- Form Start -->
            <div class="flex flex-col gap-4 divide-y-2">
                <div class="p-4 flex flex-col gap-4">
                    <!-- Username -->
                    <div class="flex flex-col gap-1">
                        <label class="font-medium" for="username">Username</label>
                        <div class="flex items-center divide-x-[1px] divide-black gap-2 pl-2 border border-black rounded bg-neutral-300">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
                                <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"/>
                            </svg>
                            <input v-model="username" type="text" placeholder="Username" autocomplete="off" id="username" name="username" class="p-2 w-full focus:outline-none">
                        </div>
                    </div>

                    <!-- Old Password -->
                    <div class="flex flex-col gap-1">
                        <label class="font-medium" for="password">Password Lama</label>
                        <div class="flex items-center divide-x-[1px] divide-black gap-2 pl-2 border border-black rounded bg-neutral-300">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="20" fill="currentColor" class="bi bi-lock" viewBox="0 0 16 16">
                                <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2m3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2M5 8h6a1 1 0 0 1 1 1v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1"/>
                            </svg>
                            <input v-model="passwordLama" type="password" placeholder="Password" autocomplete="off" id="password" name="password" class="p-2 w-full focus:outline-none">
                        </div>
                    </div>

                    <!-- New Password -->
                    <div class="flex flex-col gap-1">
                        <label class="font-medium" for="password">Password Baru</label>
                        <div class="flex items-center divide-x-[1px] divide-black gap-2 pl-2 border border-black rounded bg-neutral-300">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="20" fill="currentColor" class="bi bi-lock" viewBox="0 0 16 16">
                                <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2m3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2M5 8h6a1 1 0 0 1 1 1v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1"/>
                            </svg>
                            <input v-model="passwordBaru" type="password" placeholder="Password" autocomplete="off" id="password" name="password" class="p-2 w-full focus:outline-none">
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="p-4 w-full flex justify-end">
                    <button type="submit" class="bg-sky-500 text-white py-2 px-4 rounded shadow-md hover:bg-sky-700" @click="updateUser">Save</button>
                </div>
            </div>
            <!-- Form End -->
        </div>
    </div>
</template>

<script setup>
// Import Library
import { ref } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";
import { useAuthStore } from "@/router/auth.js";

// Variable Declaration
const router = useRouter();
const authStore = useAuthStore();
const username = ref(null);
const passwordLama = ref(null);
const passwordBaru = ref(null);
document.title = `Edit Profile | ${authStore.name}`;

// Update User Method
const updateUser = async () => {
    try {
        await axios.put(`https://lpm.unai.edu/api/user/${authStore.uid}`,
            {
                username: username.value,
                passwordLama: passwordLama.value,
                passwordBaru: passwordBaru.value
            },authStore.header);
        router.push({ name: 'Auditor' })
    } catch (error) {
        if (error.response && error.response.status === 422) {
            formErros.value = error.response.data.errors;
        } else {
            console.log(error);
        }
    }
}
</script>
