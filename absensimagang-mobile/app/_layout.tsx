import { Stack, useRouter, useSegments } from "expo-router";
import { useEffect } from "react";
import { ActivityIndicator, View } from "react-native";
import { AuthProvider, useAuth } from "../core/AuthContext";

const InitialLayout = () => {
    const { userToken, isLoading } = useAuth();
    const segments = useSegments();
    const router = useRouter();

    useEffect(() => {
        if (isLoading) return;

        const inAppGroup = segments[0] === "(app)";

        if (userToken && !inAppGroup) {
            router.replace("/(app)/(tabs)");
        } else if (!userToken && inAppGroup) {
            router.replace("/(auth)/login");
        }
    }, [userToken, segments, isLoading]);

    if (isLoading) {
        return (
            <View
                style={{
                    flex: 1,
                    justifyContent: "center",
                    alignItems: "center",
                    backgroundColor: "#1a202c",
                }}
            >
                <ActivityIndicator size="large" color="#fff" />
            </View>
        );
    }

    return <Stack screenOptions={{ headerShown: false }} />;
};

export default function RootLayout() {
    return (
        <AuthProvider>
            <InitialLayout />
        </AuthProvider>
    );
}
