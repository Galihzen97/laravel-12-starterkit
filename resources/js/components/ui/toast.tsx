"use client";

import { Toaster as Sonner } from "sonner";

export function Toaster() {
  return (
    <Sonner 
      position="top-right"
      toastOptions={{
        duration: 5000,
        classNames: {
            toast: 'bg-background text-foreground',
            success: '!bg-green-600 !text-white',
            error: '!bg-red-600 !text-white',
        },
      }}
    />
  );
}
