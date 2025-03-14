import { useEffect, useState } from "react";
import { Checkbox } from "./checkbox";
import { useLanguage } from "@/hooks/use-language";

interface ModelSelectProps {
    value: string[];
    onValueChange: (value: string[]) => void;
    options: { value: string; label: string; model: string }[];
    placeholder?: string;
    rolePermissions: Record<string, string[]>;
    selectedRole?: string;
}

export function ModelSelect({
                                value,
                                onValueChange,
                                options,
                                rolePermissions,
                                selectedRole,
                            }: ModelSelectProps) {
    const { __ } = useLanguage();
    const [localPermissions, setLocalPermissions] = useState<string[]>(value);

    useEffect(() => {
        if (selectedRole) {
            const roleBasedPermissions = rolePermissions[selectedRole] || [];
            setLocalPermissions(roleBasedPermissions);
            onValueChange(roleBasedPermissions);
        }
    }, [selectedRole]);

    useEffect(() => {
        setLocalPermissions(value);
    }, [value]);

    const groupedOptions = options.reduce((acc, option) => {
        const model = option.model || "Other";
        if (!acc[model]) acc[model] = [];
        acc[model].push(option);
        return acc;
    }, {} as Record<string, typeof options>);

    return (
        <div className="space-y-4">
            {Object.entries(groupedOptions).map(([model, modelOptions]) => (
                <div key={model} className="space-y-2">
                    <h4 className="font-medium">{model}</h4>
                    <div className="grid grid-cols-2 gap-2">
                        {modelOptions.map((option) => {
                            return (
                                <div key={option.value} className="flex items-center space-x-2">
                                    <Checkbox
                                        id={option.value}
                                        checked={localPermissions.includes(option.value)}
                                        onCheckedChange={(checked) => {
                                            let newPermissions = checked
                                                ? [...localPermissions, option.value]
                                                : localPermissions.filter((p) => p !== option.value);

                                            setLocalPermissions(newPermissions);
                                            onValueChange(newPermissions);
                                        }}
                                    />
                                    <label htmlFor={option.value} className="text-sm cursor-pointer">
                                        {option.label}
                                    </label>
                                </div>
                            );
                        })}
                    </div>
                </div>
            ))}
        </div>
    );
}
