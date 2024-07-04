import { defineStore } from "pinia";
import axios from "axios";

export const useAuthStore = defineStore({
    id: "auth",

    state() {
        const api = 'http://localhost:8000';
        return {
            accessToken: null,
            uid: null,
            name: null,
            role: null,
            idParams: null,
            api: api,
            url: `${api}/api`,
            fileUrl: `${api}/files`
        };
    },

    getters: {
        isLoggedIn() {
            return (
                this.accessToken !== null &&
                this.uid !== null &&
                this.name !== null &&
                this.role !== null
            );
        },
        header() {
            return {
                headers: {
                    Authorization: `Bearer ${this.accessToken}`,
                }
            };
        },
        Uploud() {
            return {
                headers: {
                    'Content-Type': 'multipart/form-data',
                    'Authorization': `Bearer ${this.accessToken}`,
                }
            };
        }
    },

    actions: {
        setAccessLogin(token, user, name, role) {
            this.uid = user;
            this.name = name;
            this.role = role;
            this.accessToken = token;
        },
        scope(idParams) {
            this.idParams = idParams;
        },
        removeAccessLogin() {
            this.accessToken = null;
            this.uid = null;
            this.name = null;
            this.role = null;
            this.idParams = null;
        },
        async logout() {
            try {
                await axios.post(`${this.api}/api/logout`, null, this.header);
                this.removeAccessLogin();
                console.log("Logout successful");
            } catch (error) {
                console.error("An error occurred during logout:", error.message);
            }
        },
    }
});
