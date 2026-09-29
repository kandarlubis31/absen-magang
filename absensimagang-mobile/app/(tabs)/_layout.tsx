import { Ionicons } from "@expo/vector-icons";
import { Tabs } from "expo-router";
import React from "react";

export default function TabsLayout() {
    return (
        <Tabs
            screenOptions={{
                headerShown: false,
                tabBarActiveTintColor: "#4299e1",
                tabBarInactiveTintColor: "#a0aec0",
                tabBarStyle: {
                    backgroundColor: "#1a202c",
                    borderTopColor: "#2d3748",
                },
                tabBarLabelStyle: {
                    fontSize: 12,
                },
            }}
        >
            <Tabs.Screen
                name="index"
                options={{
                    title: "Dashboard",
                    tabBarIcon: ({ color, size }) => (
                        <Ionicons
                            size={size}
                            name="home-outline"
                            color={color}
                        />
                    ),
                }}
            />
            <Tabs.Screen
                name="explore"
                options={{
                    title: "Riwayat",
                    tabBarIcon: ({ color, size }) => (
                        <Ionicons
                            size={size}
                            name="newspaper-outline"
                            color={color}
                        />
                    ),
                }}
            />
        </Tabs>
    );
}
