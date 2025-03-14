import { Label } from "@/components/ui/label";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";
import { ModelSelect } from "@/components/ui/model-select";
import { useLanguage } from "@/hooks/use-language";
import { cn } from "@/lib/utils";
import { useEffect, useState } from "react";

interface RolePermissionProps {
    role: string;
    permissions: string[];
    onRoleChange: (role: string) => void;
    onPermissionsChange: (permissions: string[]) => void;
    roles: { id: number; name: string }[];
    permissionOptions: { id: number; name: string; model?: string }[];
    rolePermissions: Record<string, string[]>;
    errors?: { role?: string; permissions?: string };
}

export function RolePermission({
                                   role,
                                   permissions,
                                   onRoleChange,
                                   onPermissionsChange,
                                   roles,
                                   permissionOptions,
                                   rolePermissions,
                                   errors,
                               }: RolePermissionProps) {
    const { __ } = useLanguage();
    const [selectedPermissions, setSelectedPermissions] = useState<string[]>(permissions);

    useEffect(() => {
        if (role) {
            const roleBasedPermissions = rolePermissions[role] || [];
            setSelectedPermissions(roleBasedPermissions);
            onPermissionsChange(roleBasedPermissions);
        }
    }, [role]);

    return (
        <div className="space-y-4">
            {/* Role Selection */}
            <div className="space-y-2">
                <Label htmlFor="role">{__('Role')}</Label>
                <Select value={role} onValueChange={(value) => onRoleChange(value)}>
                    <SelectTrigger className={cn(errors?.role && "border-red-500")}>
                        <SelectValue placeholder={__('Select a role')} />
                    </SelectTrigger>
                    <SelectContent>
                        {roles.map((role) => (
                            <SelectItem key={role.id} value={role.name}>
                                {role.name}
                            </SelectItem>
                        ))}
                    </SelectContent>
                </Select>
                {errors?.role && <p className="text-sm text-red-500">{errors.role}</p>}
            </div>

            {/* Permissions Selection */}
            <ModelSelect
                key={role} // Ensures rerender when role changes
                value={selectedPermissions}
                onValueChange={(updatedPermissions) => {
                    setSelectedPermissions(updatedPermissions);
                    onPermissionsChange(updatedPermissions);
                }}
                options={permissionOptions.map((permission) => ({
                    value: permission.name,
                    label: permission.name,
                    model: permission.model || "Other",
                }))}
                placeholder={__('Select permissions')}
                rolePermissions={rolePermissions}
                selectedRole={role}
            />
        </div>
    );
}
