import { useMemo } from 'react';

export function useRolePermissions(rolePermissions: Record<string, string[]>) {
    return useMemo(() => {
        if (!Array.isArray(rolePermissions)) {
            return {};
        }

        return rolePermissions.reduce((acc, { role, permissions }) => {
            if (role && Array.isArray(permissions)) {
                acc[role] = permissions;
            }
            return acc;
        }, {} as Record<string, string[]>);
    }, [rolePermissions]);
}
