import { usePage } from "@inertiajs/vue3";

export function useRole() {
    const hasRole = (roles) => {
    
        if (!roles || (Array.isArray(roles) && roles.length === 0)) {
            throw new Error("Role(s) must be specified for access control.");
        }

        const { props } = usePage();
        const user = props.auth?.user;

        if (!user) {
            return false;
        }


        const rolesToCheck = Array.isArray(roles) ? roles : [roles];

    
        const userRole = user.type;

        if (!userRole) {
            return false;
        }

        return rolesToCheck.includes(userRole);
    };

    return { hasRole };
}
