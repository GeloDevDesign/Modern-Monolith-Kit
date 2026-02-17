export const config = {
    appName: "GelGrid",
    isProduction: import.meta.env.VITE_APP_ENV === "production",
    theme: {
        // mode only "light" or "dark"
        mode: "dark",
        defaultColor: "blue",
        darkModeEnabled: true,
    },
    images: {
        siteIcon: "",
        companyLogo: "",
    },
};
