import axios from "axios";
import React, { useCallback, useEffect, useState } from "react";
import {
    ActivityIndicator,
    Alert,
    RefreshControl,
    SafeAreaView,
    ScrollView,
    StatusBar,
    StyleSheet,
    Text,
    TouchableOpacity,
    View,
} from "react-native";
import { useAuth } from "../../../core/AuthContext";

const API_BASE_URL = "http://127.0.0.1:8000/api";

export default function DashboardScreen() {
    const { userToken, signOut } = useAuth();
    const [user, setUser] = useState(null);
    const [absensiStatus, setAbsensiStatus] = useState(null);
    const [isLoading, setIsLoading] = useState(true);
    const [isButtonLoading, setIsButtonLoading] = useState(false);

    const fetchDashboardData = useCallback(async () => {
        if (!userToken) return;
        setIsLoading(true);
        const apiClient = axios.create({
            baseURL: API_BASE_URL,
            headers: { Authorization: `Bearer ${userToken}` },
        });
        try {
            const [userResponse, absensiResponse] = await Promise.all([
                apiClient.get("/user"),
                apiClient.get("/absensi/status-hari-ini"),
            ]);
            console.log("Absensi status response:", absensiResponse.data);

            setUser(userResponse.data);
            setAbsensiStatus(absensiResponse.data);
        } catch (error) {
            console.error(
                "Gagal memuat data dashboard:",
                error.response || error
            );
            if (error.response?.status === 401) {
                Alert.alert(
                    "Sesi Berakhir",
                    "Sesi Anda telah berakhir, silakan login kembali."
                );
                signOut();
            } else {
                Alert.alert("Error", "Gagal memuat data.");
            }
        } finally {
            setIsLoading(false);
        }
    }, [userToken, signOut]);

    useEffect(() => {
        fetchDashboardData();
    }, [fetchDashboardData]);

    const handleCheckIn = async () => {
        setIsButtonLoading(true);
        const apiClient = axios.create({
            baseURL: API_BASE_URL,
            headers: { Authorization: `Bearer ${userToken}` },
        });
        try {
            await apiClient.post("/check-in");
            Alert.alert("Sukses", "Berhasil absen masuk!");
            await fetchDashboardData();
        } catch (error) {
            Alert.alert(
                "Error",
                error.response?.data?.message || "Gagal absen masuk."
            );
        } finally {
            setIsButtonLoading(false);
        }
    };

    const handleCheckOut = async () => {
        setIsButtonLoading(true);
        const apiClient = axios.create({
            baseURL: API_BASE_URL,
            headers: { Authorization: `Bearer ${userToken}` },
        });
        try {
            await apiClient.post("/check-out");
            Alert.alert("Sukses", "Berhasil absen pulang!");
            await fetchDashboardData();
        } catch (error) {
            Alert.alert(
                "Error",
                error.response?.data?.message || "Gagal absen pulang."
            );
        } finally {
            setIsButtonLoading(false);
        }
    };

    const renderStatusCard = () => {
        let statusText = "Memuat status...";
        let statusColor = "#4a5568";

        if (absensiStatus?.status === "belum_absen") {
            statusText = "Anda belum absen hari ini.";
            statusColor = "#e53e3e";
        } else if (absensiStatus?.jam_masuk && !absensiStatus?.jam_keluar) {
            statusText = `Masuk: ${absensiStatus.jam_masuk}`;
            statusColor = "#38a169";
        } else if (absensiStatus?.jam_keluar) {
            statusText = `Selesai. Pulang: ${absensiStatus.jam_keluar}`;
            statusColor = "#4299e1";
        }

        return (
            <View style={[styles.statusCard, { backgroundColor: statusColor }]}>
                <Text style={styles.statusText}>{statusText}</Text>
            </View>
        );
    };

    const renderActionButton = () => {
        if (absensiStatus?.status === "belum_absen") {
            return (
                <TouchableOpacity
                    style={styles.button}
                    onPress={handleCheckIn}
                    disabled={isButtonLoading}
                >
                    {isButtonLoading ? (
                        <ActivityIndicator color="#fff" />
                    ) : (
                        <Text style={styles.buttonText}>Absen Masuk</Text>
                    )}
                </TouchableOpacity>
            );
        } else if (absensiStatus?.jam_masuk && !absensiStatus?.jam_keluar) {
            return (
                <TouchableOpacity
                    style={[styles.button, styles.buttonSecondary]}
                    onPress={handleCheckOut}
                    disabled={isButtonLoading}
                >
                    {isButtonLoading ? (
                        <ActivityIndicator color="#fff" />
                    ) : (
                        <Text style={styles.buttonText}>Absen Pulang</Text>
                    )}
                </TouchableOpacity>
            );
        }
        return (
            <Text style={styles.infoText}>
                Anda sudah menyelesaikan absensi hari ini.
            </Text>
        );
    };

    if (isLoading && !user) {
        return (
            <View style={[styles.container, styles.center]}>
                <ActivityIndicator size="large" color="#fff" />
            </View>
        );
    }

    return (
        <SafeAreaView style={styles.container}>
            <StatusBar barStyle="light-content" backgroundColor="#1a202c" />
            <ScrollView
                contentContainerStyle={styles.scrollView}
                refreshControl={
                    <RefreshControl
                        refreshing={isLoading}
                        onRefresh={fetchDashboardData}
                        tintColor="#fff"
                    />
                }
            >
                <View style={styles.header}>
                    <Text style={styles.title}>Selamat Datang,</Text>
                    <Text style={styles.subtitle}>
                        {user?.name || "Mahasiswa"}
                    </Text>
                </View>
                {renderStatusCard()}
                <View style={styles.actionContainer}>
                    {renderActionButton()}
                </View>
                <TouchableOpacity onPress={signOut} style={styles.logoutButton}>
                    <Text style={styles.logoutText}>Logout</Text>
                </TouchableOpacity>
            </ScrollView>
        </SafeAreaView>
    );
}

const styles = StyleSheet.create({
    container: { flex: 1, backgroundColor: "#1a202c" },
    center: { justifyContent: "center", alignItems: "center" },
    scrollView: { flexGrow: 1, padding: 20 },
    header: { marginBottom: 30 },
    title: { fontSize: 28, fontWeight: "300", color: "#a0aec0" },
    subtitle: { fontSize: 36, fontWeight: "bold", color: "#fff" },
    statusCard: {
        borderRadius: 12,
        padding: 20,
        marginBottom: 40,
        alignItems: "center",
        minHeight: 60,
        justifyContent: "center",
    },
    statusText: {
        color: "#fff",
        fontSize: 16,
        fontWeight: "600",
        textAlign: "center",
    },
    actionContainer: {
        flex: 1,
        justifyContent: "center",
        alignItems: "center",
    },
    button: {
        width: "100%",
        backgroundColor: "#38a169",
        borderRadius: 12,
        paddingVertical: 18,
        alignItems: "center",
        justifyContent: "center",
        minHeight: 60,
    },
    buttonSecondary: { backgroundColor: "#dd6b20" },
    buttonText: { color: "#fff", fontSize: 18, fontWeight: "bold" },
    infoText: { color: "#a0aec0", fontSize: 16, textAlign: "center" },
    logoutButton: { marginTop: 40, padding: 10, alignSelf: "center" },
    logoutText: { color: "#e53e3e", fontSize: 16, fontWeight: "600" },
});
