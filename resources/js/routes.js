import { route } from "ziggy-js";

export default function sidebarRoutes() {
    return [
        {
            group: "General",
            items: [
                {
                    label: "Dashboard",
                    icon: "pi pi-fw pi-home",
                    url: route("dashboard.index"),
                    roles: ["admin", "super_admin", "user"],
                    variant: "primary",
                },
            ],
        },
        {
            group: "Tools",
            items: [
                {
                    label: "Settings",
                    icon: "pi pi-fw pi-cog",
                    expanded: false,
                    roles: ["admin", "super_admin"],
                    children: [
                        {
                            label: "General",
                            expanded: false,
                            url: route("settings.general"),
                            roles: ["admin", "super_admin"],
                        },
                        {
                            label: "Admin User",
                            expanded: false,
                            roles: ["admin", "super_admin"],
                            children: [
                                {
                                    label: "View Users",
                                    url: route("users.index"),
                                    roles: ["admin", "super_admin"],
                                    badge: "pi pi-users",
                                    badgeColor: "bg-blue-500 text-white",
                                },
                                {
                                    label: "Add Users",
                                    url: route("users.create"),
                                    roles: ["admin", "super_admin"],
                                    badge: "New",
                                    badgeColor: "bg-green-500 text-white",
                                },
                            ],
                        },
                        {
                            label: "Backups",
                            expanded: false,
                            url: "/backups",
                            roles: ["admin", "super_admin"],
                        },
                        {
                            label: "Activity Log",
                            expanded: false,
                            url: "/activity-logs",
                            roles: ["admin", "super_admin"],
                            badge: "10",
                            badgeColor: "bg-blue-500 text-white",
                        },
                    ],
                },
            ],
        },
    ];
}
