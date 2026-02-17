import "./bootstrap";

import { createApp, h } from "vue";
import type { DefineComponent } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import PrimeVue from "primevue/config";
import { definePreset } from "@primevue/themes";
import { createPinia } from "pinia";
import { ZiggyVue } from "ziggy-js";
import DefaultPreset from "./presets/DefaultPreset.js";
import Tooltip from "primevue/tooltip";
import BadgeDirective from "primevue/badgedirective";
import Ripple from "primevue/ripple";
import StyleClass from "primevue/styleclass";
import Button from "primevue/button";
import Dialog from "primevue/dialog";
import InputText from "primevue/inputtext";
import Toast from "primevue/toast";
import ToastService from "primevue/toastservice";
import Avatar from "primevue/avatar";
import ConfirmDialog from "primevue/confirmdialog";
import ConfirmationService from "primevue/confirmationservice";
import Menu from "primevue/menu";
import OverlayBadge from "primevue/overlaybadge";
import Drawer from "primevue/drawer";
import Password from "primevue/password";
import Checkbox from "primevue/checkbox";
import Message from "primevue/message";
import FloatLabel from "primevue/floatlabel";
import Card from "primevue/card";
import Tabs from "primevue/tabs";
import TabList from "primevue/tablist";
import Tab from "primevue/tab";
import TabPanels from "primevue/tabpanels";
import TabPanel from "primevue/tabpanel";
import Chart from "primevue/chart";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import SelectButton from "primevue/selectbutton";
import Breadcrumb from "primevue/breadcrumb";
import Timeline from "primevue/timeline";
import Editor from "primevue/editor";
import Textarea from "primevue/textarea";
import Select from "primevue/select";
import IconField from "primevue/iconfield";
import Tag from "primevue/tag";
import InputIcon from "primevue/inputicon";
import DatePicker from "primevue/datepicker";
import Calendar from "primevue/calendar";
import AppLogo from "@/Components/AppLogo.vue";
import Panel from "primevue/panel";

createInertiaApp({
    resolve: (name) => {
        const pages = import.meta.glob<DefineComponent>("./Pages/**/*.vue", {
            eager: true
        });
        return pages[`./Pages/${name}.vue`];
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(createPinia())
            .use(PrimeVue, {
                theme: {
                    preset: DefaultPreset,
                    options: {
                        prefix: "p",
                        darkModeSelector: ".dark",
                        cssLayer: false
                    }
                }
            })
            .component("Button", Button)
            .component("Menu", Menu)
            .component("OverlayBadge", OverlayBadge)
            .component("Drawer", Drawer)
            .component("Password", Password)
            .component("Checkbox", Checkbox)
            .component("Message", Message)
            .component("FloatLabel", FloatLabel)
            .component("DatePicker", DatePicker)
            .component("Card", Card)
            .component("Tabs", Tabs)
            .component("TabList", TabList)
            .component("IconField", IconField)
            .component("Tab", Tab)
            .component("Select", Select)
            .component("TabPanels", TabPanels)
            .component("TabPanel", TabPanel)
            .component("Chart", Chart)
            .component("Tag", Tag)
            .component("DataTable", DataTable)
            .component("Column", Column)
            .component("SelectButton", SelectButton)
            .component("Breadcrumb", Breadcrumb)
            .component("Timeline", Timeline)
            .component("Editor", Editor)
            .component("Textarea", Textarea)
            .component("AppLogo", AppLogo)
            .component("Panel", Panel)
            .directive("tooltip", Tooltip)
            .directive("badge", BadgeDirective)
            .directive("ripple", Ripple)
            .directive("styleclass", StyleClass)
            .component("Dialog", Dialog)
            .component("InputText", InputText)
            .component("InputIcon", InputIcon)
            .component("Toast", Toast)
            .component("Avatar", Avatar)
            .component("ConfirmDialog", ConfirmDialog)
            .use(ToastService)
            .use(ConfirmationService)
            .mount(el);
    }
});
