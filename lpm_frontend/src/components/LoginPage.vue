
<template>
    <div class="bg-white flex justify-center items-center h-screen p-5">
        <div class="flex flex-col w-full md:w-1/2 xl:w-2/5 2xl:w-2/5 3xl:w-1/3 p-8 md:p-10 2xl:p-12 3xl:p-14 bg-[#ffffff] rounded-2xl shadow-xl">
            <!-- Header Start -->
            <div class="flex items-center gap-3 pb-4">
                <!-- Logo -->
                <div>
                    <img src="https://unai.edu/wp-content/uploads/2023/09/Logo-Unai.png" class="w-[65px] md:[80px]" alt="Logo">
                </div>
                <h1 class="text-lg font-bold text-[#4B5563] text-[#4B5563] my-auto flex flex-col md:text-2xl">Lembaga Penjaminan Mutu (LPM) <span class="text-base md:text-xl">Universitas Advent Indonesia</span></h1>
            </div>
            <!-- Header End -->

            <!-- Error Message Start -->
            <div class="bg-red-400 text-black font-bold p-2 rounded flex gap-2 items-center" v-if="errorMessage">
                <svg height="30" width="20" style="overflow:visible;enable-background:new 0 0 32 32" viewBox="0 0 32 32"  xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g><g id="Error_1_"><g id="Error"><circle cx="16" cy="16" id="BG" r="16" style="fill:#D72828;"/><path d="M14.5,25h3v-3h-3V25z M14.5,6v13h3V6H14.5z" id="Exclamatory_x5F_Sign" style="fill:#000000;"/></g></g></g></svg>
                <span class="leading-0">{{errorMessage}}</span>
            </div>
            <!-- Error Message End -->

            <!-- Form Login Start -->
            <form class="flex flex-col" @submit.prevent="onSubmit">
                <!-- Username -->
                <div class="pb-2">
                    <label for="username" class="block mb-2 font-semibold text-[#111827]">Username</label>
                    <div class="relative text-gray-400">
                        <span class="absolute inset-y-0 left-0 flex items-center p-1 pl-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"  class="bi bi-person-circle" viewBox="0 0 16 16">
                                <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                                <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
                            </svg>
                        </span>
                        <input type="text" v-model="UserName" class="pl-12 mb-2 bg-gray-50 text-gray-600 border focus:border-transparent border-gray-300 sm:text-sm rounded-lg ring ring-transparent focus:ring-1 focus:outline-none focus:ring-gray-400 block w-full p-2.5 rounded-l-lg py-3 px-4" placeholder="Username" autofocus>
                    </div>
                </div>

                <!-- Password -->
                <div class="pb-6">
                    <label for="password" class="block mb-2 font-semibold text-[#111827]">Password</label>
                    <div class="relative text-gray-400">
                        <span class="absolute inset-y-0 left-0 flex items-center p-1 pl-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 50 50">
                                <path d="M 25 2 C 17.832484 2 12 7.8324839 12 15 L 12 21 L 8 21 C 6.3550302 21 5 22.35503 5 24 L 5 47 C 5 48.64497 6.3550302 50 8 50 L 42 50 C 43.64497 50 45 48.64497 45 47 L 45 24 C 45 22.35503 43.64497 21 42 21 L 38 21 L 38 15 C 38 7.8324839 32.167516 2 25 2 z M 25 4 C 31.086484 4 36 8.9135161 36 15 L 36 21 L 14 21 L 14 15 C 14 8.9135161 18.913516 4 25 4 z M 8 23 L 42 23 C 42.56503 23 43 23.43497 43 24 L 43 47 C 43 47.56503 42.56503 48 42 48 L 8 48 C 7.4349698 48 7 47.56503 7 47 L 7 24 C 7 23.43497 7.4349698 23 8 23 z M 13 34 A 2 2 0 0 0 11 36 A 2 2 0 0 0 13 38 A 2 2 0 0 0 15 36 A 2 2 0 0 0 13 34 z M 21 34 A 2 2 0 0 0 19 36 A 2 2 0 0 0 21 38 A 2 2 0 0 0 23 36 A 2 2 0 0 0 21 34 z M 29 34 A 2 2 0 0 0 27 36 A 2 2 0 0 0 29 38 A 2 2 0 0 0 31 36 A 2 2 0 0 0 29 34 z M 37 34 A 2 2 0 0 0 35 36 A 2 2 0 0 0 37 38 A 2 2 0 0 0 39 36 A 2 2 0 0 0 37 34 z"></path>
                            </svg>
                        </span>
                        <input :type="pass.show ? 'password' : 'text'" id="password" v-model="pass.password" placeholder="••••••••••" class="pl-12 mb-2 bg-gray-50 text-gray-600 border focus:border-transparent border-gray-300 sm:text-sm rounded-lg ring ring-transparent focus:ring-1 focus:outline-none focus:ring-gray-400 block w-full p-2.5 rounded-l-lg py-3 px-4">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5">
                            <svg v-if="pass.show" @click="togglePasswordVisibility" class="h-4 text-gray-700 cursor-pointer" fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                                <path fill="currentColor" d="M572.52 241.4C518.29 135.59 410.93 64 288 64S57.68 135.64 3.48 241.41a32.35 32.35 0 0 0 0 29.19C57.71 376.41 165.07 448 288 448s230.32-71.64 284.52-177.41a32.35 32.35 0 0 0 0-29.19zM288 400a144 144 0 1 1 144-144 143.93 143.93 0 0 1-144 144zm0-240a95.31 95.31 0 0 0-25.31 3.79 47.85 47.85 0 0 1-66.9 66.9A95.78 95.78 0 1 0 288 160z"></path>
                            </svg>
                            <svg v-else @click="togglePasswordVisibility" class="h-4 text-gray-700 cursor-pointer" fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512">
                                <path fill="currentColor" d="M320 400c-75.85 0-137.25-58.71-142.9-133.11L72.2 185.82c-13.79 17.3-26.48 35.59-36.72 55.59a32.35 32.35 0 0 0 0 29.19C89.71 376.41 197.07 448 320 448c26.91 0 52.87-4 77.89-10.46L346 397.39a144.13 144.13 0 0 1-26 2.61zm313.82 58.1l-110.55-85.44a331.25 331.25 0 0 0 81.25-102.07 32.35 32.35 0 0 0 0-29.19C550.29 135.59 442.93 64 320 64a308.15 308.15 0 0 0-147.32 37.7L45.46 3.37A16 16 0 0 0 23 6.18L3.37 31.45A16 16 0 0 0 6.18 53.9l588.36 454.73a16 16 0 0 0 22.46-2.81l19.64-25.27a16 16 0 0 0-2.82-22.45zm-183.72-142l-39.3-30.38A94.75 94.75 0 0 0 416 256a94.76 94.76 0 0 0-121.31-92.21A47.65 47.65 0 0 1 304 192a46.64 46.64 0 0 1-1.54 10l-73.61-56.89A142.31 142.31 0 0 1 320 112a143.92 143.92 0 0 1 144 144c0 21.63-5.29 41.79-13.9 60.11z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Login Animation -->
                <button type="submit" class="w-full text-[#FFFFFF] bg-[#4F46E5] focus:ring-4 focus:outline-none focus:ring-primary-300 font-semibold rounded-lg px-5 py-2.5 justify-center mb-6 flex gap-2 items-center" disabled v-if="login">
                        <svg class="text-gray-300 animate-spin" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg" width="18" height="18">
                            <path d="M32 3C35.8083 3 39.5794 3.75011 43.0978 5.20749C46.6163 6.66488 49.8132 8.80101 52.5061 11.4939C55.199 14.1868 57.3351 17.3837 58.7925 20.9022C60.2499 24.4206 61 28.1917 61 32C61 35.8083 60.2499 39.5794 58.7925 43.0978C57.3351 46.6163 55.199 49.8132 52.5061 52.5061C49.8132 55.199 46.6163 57.3351 43.0978 58.7925C39.5794 60.2499 35.8083 61 32 61C28.1917 61 24.4206 60.2499 20.9022 58.7925C17.3837 57.3351 14.1868 55.199 11.4939 52.5061C8.801 49.8132 6.66487 46.6163 5.20749 43.0978C3.7501 39.5794 3 35.8083 3 32C3 28.1917 3.75011 24.4206 5.2075 20.9022C6.66489 17.3837 8.80101 14.1868 11.4939 11.4939C14.1868 8.80099 17.3838 6.66487 20.9022 5.20749C24.4206 3.7501 28.1917 3 32 3L32 3Z" stroke="currentColor" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M32 3C36.5778 3 41.0906 4.08374 45.1692 6.16256C49.2477 8.24138 52.7762 11.2562 55.466 14.9605C58.1558 18.6647 59.9304 22.9531 60.6448 27.4748C61.3591 31.9965 60.9928 36.6232 59.5759 40.9762" stroke="currentColor" stroke-width="5" stroke-linecap="round" stroke-linejoin="round" class="text-gray-900"></path>
                        </svg>
                        Login ...
                </button>

                <!-- Login Button -->
                <button type="submit" class="w-full text-[#FFFFFF] bg-[#4F46E5] focus:ring-4 focus:outline-none focus:ring-primary-300 font-semibold rounded-lg px-5 py-2.5 text-center mb-6" v-else>Login</button>
            </form>
            <!-- Form Login End -->
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
const UserName = ref();
const errorMessage = ref(false);
const login = ref(false);
const pass = ref({
    show: true,
    password: ''
})
document.title = `Login | Lembaga Penjaminan Mutu`;

// Submit Method
async function onSubmit() {
    login.value = true;
    try {
        // Fetch CSRF token
        await axios.get("https://lpm.unai.edu/sanctum/csrf-cookie");

        const response = await axios.post(`${authStore.url}/login`, {
            username: UserName.value,
            password: pass.value.password,
        });

        authStore.setAccessLogin(response.data.access_token, response.data.Uid, response.data.name, response.data.role);
        if(response.data.role ==="auditee"){
        router.push({name:'Audite'})
        }
        else if(response.data.role === "auditor"){
            router.push({ name: 'LPM' })
        }
        else if (response.data.role == "admin"){
            router.push({ name: 'Admin' });
        }
    } catch (error) {
        // Check if the error is due to validation
        if (error.response &&
            error.response.status === 422 &&
            error.response.data.errors &&
            error.response.data.errors.username) {
            // Set the error message from the server response
            errorMessage.value = "402: Username dan Password tidak tepat";
        } else {
            // Set a generic error message
            errorMessage.value = "503: Server Lagi Down";
        }

        login.value = false
    }
}

// Password Visibility Method
const togglePasswordVisibility=()=>{
    pass.value.show = !pass.value.show;
}
</script>
