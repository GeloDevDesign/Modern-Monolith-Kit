import { definePreset } from "@primevue/themes";
import Lara from "@primevue/themes/lara";
import Aura from "@primevue/themes/aura";
import Nora from "@primevue/themes/nora";
import Material from "@primevue/themes/material";

import { config } from "@/Utils/config";

const primary = config.theme.defaultColor.toLowerCase();

const DefaultPreset = definePreset(Aura, {
    semantic: {
        primary: {
            50: `{${primary}.50}`,
            100: `{${primary}.100}`,
            200: `{${primary}.200}`,
            300: `{${primary}.300}`,
            400: `{${primary}.400}`,
            500: `{${primary}.500}`,
            600: `{${primary}.600}`,
            700: `{${primary}.700}`,
            800: `{${primary}.800}`,
            900: `{${primary}.900}`,
            950: `{${primary}.950}`,
        },
        colorScheme: {
            light: {
                primary: {
                    50: `{${primary}.50}`,
                    100: `{${primary}.100}`,
                    200: `{${primary}.200}`,
                    300: `{${primary}.300}`,
                    400: `{${primary}.400}`,
                    500: `{${primary}.500}`,
                    600: `{${primary}.600}`,
                    700: `{${primary}.700}`,
                    800: `{${primary}.800}`,
                    900: `{${primary}.900}`,
                    950: `{${primary}.950}`,
                },

                // primary: {
                //     50: "{lime.200}",
                //     100: "{lime.100}",
                //     200: "{lime.400}",
                //     300: "{lime.500}",
                //     400: "{lime.600}",
                //     500: "{lime.800}",
                //     600: "{lime.900}",
                //     700: "{lime.950}",
                //     800: "{lime.950}",
                //     900: "{lime.950}",
                //     950: "{lime.950}",
                // },
            },
            dark: {
                primary: {
                    50: `{${primary}.50}`,
                    100: `{${primary}.300}`,
                    200: `{${primary}.400}`,
                    300: `{${primary}.500}`,
                    400: `{${primary}.600}`,
                    500: `{${primary}.700}`,
                    600: `{${primary}.800}`,
                    700: `{${primary}.900}`,
                    800: `{${primary}.950}`,
                    900: `{${primary}.950}`,
                    950: `{${primary}.950}`,
                },
            },
        },
    },
});

export default DefaultPreset;
