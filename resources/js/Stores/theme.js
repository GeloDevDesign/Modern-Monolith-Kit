import { defineStore } from "pinia";
import { ref } from "vue";
import { TAILWIND_COLORS } from "@/Constants/colors";
import { config } from "@/Utils/config";

export const useThemeStore = defineStore("theme", () => {

    const isDark = ref(false);

    // GET THE PRIMARY COLOR
    const primaryColor = ref(
        TAILWIND_COLORS.find(
            (c) =>
                c.name.toLowerCase() ===
                config.theme.defaultColor.toLowerCase(),
        ),
    );

    const initTheme = () => {
        // Initialize Dark Mode
        let prefersDark;

        if (config.isProduction) {
            prefersDark = config.theme.mode === "dark";
        } else {
            const storedTheme = localStorage.getItem("theme");
            prefersDark = storedTheme
                ? storedTheme === "dark"
                : config.theme.mode === "dark";
        }

        if (prefersDark && config.theme.darkModeEnabled) {
            isDark.value = true;
            document.documentElement.classList.add("dark");
        } else {
            isDark.value = false;
            document.documentElement.classList.remove("dark");
        }

        // Initialize Primary Color
        if (config.isProduction) {
            setPrimaryColor(primaryColor.value);
        } else {
            const storedColorName = localStorage.getItem("primaryColor");
            if (storedColorName) {
                const color = TAILWIND_COLORS.find(
                    (c) => c.name === storedColorName,
                );
                if (color) {
                    setPrimaryColor(color);
                } else {
                    setPrimaryColor(primaryColor.value);
                }
            } else {
                setPrimaryColor(primaryColor.value);
            }
        }
    };

    const toggleTheme = () => {
        if (config.theme.darkModeEnabled) {
            isDark.value = !isDark.value;
            updateTheme();
        }
    };

    const setTheme = (value) => {
        if (config.theme.darkModeEnabled) {
            isDark.value = value === "dark";
            updateTheme();
        }
    };

    const updateTheme = () => {
        if (isDark.value && config.theme.darkModeEnabled) {
            document.documentElement.classList.add("dark");
            if (!config.isProduction) {
                localStorage.setItem("theme", "dark");
            }
        } else {
            document.documentElement.classList.remove("dark");
            if (!config.isProduction) {
                localStorage.setItem("theme", "light");
            }
        }
    };

    const setPrimaryColor = (color) => {
        primaryColor.value = color;
        if (!config.isProduction) {
            localStorage.setItem("primaryColor", color.name);
        }

        // Update CSS variables for PrimeVue
        const palette = color.palette;
        for (const [shade, value] of Object.entries(palette)) {
            document.documentElement.style.setProperty(
                `--p-primary-${shade}`,
                value,
            );
        }
    };

    return {
        isDark,
        primaryColor,
        initTheme,
        toggleTheme,
        setTheme,
        setPrimaryColor,
    };
});
