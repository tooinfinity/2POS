import * as React from 'react';
import { cn } from '@/lib/utils';
import { Check } from 'lucide-react';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';

export interface Option {
    label: string;
    value: string;
    model: string;
}

interface SelectProps {
    options: Option[];
    value: string;
    onValueChange: (value: string) => void;
    placeholder?: string;
    error?: string;
    rolePermissions?: { [key: string]: string[] };
    selectedRole?: string;
}

export function ModelSelect({
    options = [],
    value = '',
    onValueChange,
    error,
    rolePermissions = {},
    selectedRole = ''
}: SelectProps) {
    const [activeTab, setActiveTab] = React.useState<string>('');

    const isPermissionChecked = (permissionValue: string) => {
        if (!selectedRole || !rolePermissions[selectedRole]) {
            return value === permissionValue;
        }
        return rolePermissions[selectedRole].includes(permissionValue);
    };

    const groupedOptions = React.useMemo(() => {
        const groups: { [key: string]: Option[] } = {};
        if (!Array.isArray(options)) return groups;

        options.forEach((option) => {
            if (!groups[option.model]) {
                groups[option.model] = [];
            }
            groups[option.model].push(option);
        });
        return groups;
    }, [options]);

    React.useEffect(() => {
        if (Object.keys(groupedOptions).length > 0 && !activeTab) {
            setActiveTab(Object.keys(groupedOptions)[0]);
        }
    }, [groupedOptions, activeTab]);

    return (
        <div className={cn('rounded-md border', error && 'border-destructive')}>
            <Tabs value={activeTab} onValueChange={setActiveTab} className="w-full">
                <TabsList className="w-full">
                    {Object.keys(groupedOptions).map((model) => (
                        <TabsTrigger key={model} value={model} className="flex-1">
                            {model}
                        </TabsTrigger>
                    ))}
                </TabsList>
                {Object.entries(groupedOptions).map(([model, modelOptions]) => (
                    <TabsContent key={model} value={model} className="border-none p-4">
                        <div className="space-y-2">
                            {modelOptions.map((option) => (
                                <label
                                    key={option.value}
                                    className="flex items-center gap-2 rounded-lg border p-4 hover:bg-muted"
                                >
                                    <div
                                        className={cn(
                                            'flex h-4 w-4 items-center justify-center rounded-sm border',
                                            isPermissionChecked(option.value) ? 'border-primary bg-primary text-primary-foreground' : 'border-primary'
                                        )}
                                    >
                                        {isPermissionChecked(option.value) && <Check className="h-3 w-3" />}
                                    </div>
                                    <span>{option.label}</span>
                                    <input
                                        type="radio"
                                        className="hidden"
                                        checked={isPermissionChecked(option.value)}
                                        onChange={() => onValueChange(option.value)}
                                        disabled={selectedRole !== ''}
                                    />
                                </label>
                            ))}
                        </div>
                    </TabsContent>
                ))}
            </Tabs>
        </div>
    );
}
