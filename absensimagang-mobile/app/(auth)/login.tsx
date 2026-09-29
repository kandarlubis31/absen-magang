import axios from "axios";
import React, { useState } from "react";
import {
    ActivityIndicator,
    Alert,
    Keyboard,
    KeyboardAvoidingView,
    Platform,
    StatusBar,
    StyleSheet,
    Text,
    TextInput,
    TouchableOpacity,
    TouchableWithoutFeedback,
    View,
} from "react-native";
import { useAuth } from "../../core/AuthContext";

const API_BASE_URL = "http://127.0.0.1:8000/api";

export default function LoginScreen() {
    const [nim, setNim] = useState("");
    const [password, setPassword] = useState("");
    const [isLoading, setIsLoading] = useState(false);
    const { signIn } = useAuth();

    const handleLogin = async () => {
        Keyboard.dismiss();
        if (!nim.trim() || !password.trim()) {
            Alert.alert(
                "Input Tidak Valid",
                "NIM dan Password tidak boleh kosong."
            );
            return;
        }
        setIsLoading(true);
        try {
            const response = await axios.post(`${API_BASE_URL}/login`, {
                nim: nim.trim(),
                password: password,
            });
            if (response.data && response.data.access_token) {
                signIn(response.data.access_token);
            } else {
                throw new Error("Token tidak ditemukan dalam response server");
            }
        } catch (error) {
            let errorMessage = "Terjadi kesalahan. Coba lagi.";
            if (error.response) {
                errorMessage =
                    error.response.data?.message ||
                    `Error: ${error.response.status}`;
            } else if (error.request) {
                errorMessage = "Tidak dapat terhubung ke server.";
            } else {
                errorMessage = error.message;
            }
            Alert.alert("Login Gagal", errorMessage);
        } finally {
            setIsLoading(false);
        }
    };

    return (
        <KeyboardAvoidingView
            behavior={Platform.OS === "ios" ? "padding" : "height"}
            style={styles.container}
        >
            <TouchableWithoutFeedback onPress={Keyboard.dismiss}>
                <View style={styles.innerContainer}>
                    <StatusBar
                        barStyle="light-content"
                        backgroundColor="#1a202c"
                    />
                    <Text style={styles.title}>Sistem Absensi</Text>
                    <Text style={styles.subtitle}>Login Mahasiswa Magang</Text>
                    <TextInput
                        style={styles.input}
                        placeholder="Masukkan NIM"
                        placeholderTextColor="#888"
                        value={nim}
                        onChangeText={setNim}
                        autoCapitalize="none"
                        editable={!isLoading}
                    />
                    <TextInput
                        style={styles.input}
                        placeholder="Masukkan Password"
                        placeholderTextColor="#888"
                        value={password}
                        onChangeText={setPassword}
                        secureTextEntry
                        editable={!isLoading}
                    />
                    <TouchableOpacity
                        style={[
                            styles.button,
                            isLoading ? styles.buttonDisabled : null,
                        ]}
                        onPress={handleLogin}
                        disabled={isLoading}
                    >
                        {isLoading ? (
                            <ActivityIndicator size="small" color="#fff" />
                        ) : (
                            <Text style={styles.buttonText}>Login</Text>
                        )}
                    </TouchableOpacity>
                </View>
            </TouchableWithoutFeedback>
        </KeyboardAvoidingView>
    );
}

const styles = StyleSheet.create({
    container: { flex: 1, backgroundColor: "#1a202c" },
    innerContainer: {
        flex: 1,
        alignItems: "center",
        justifyContent: "center",
        paddingHorizontal: 20,
    },
    title: {
        fontSize: 32,
        fontWeight: "bold",
        color: "#fff",
        marginBottom: 10,
    },
    subtitle: { fontSize: 16, color: "#a0aec0", marginBottom: 40 },
    input: {
        width: "100%",
        backgroundColor: "#2d3748",
        color: "#fff",
        borderRadius: 8,
        paddingHorizontal: 15,
        paddingVertical: 12,
        fontSize: 16,
        marginBottom: 15,
    },
    button: {
        width: "100%",
        backgroundColor: "#4299e1",
        borderRadius: 8,
        paddingVertical: 15,
        alignItems: "center",
        marginTop: 10,
    },
    buttonDisabled: { backgroundColor: "#4a5568" },
    buttonText: { color: "#fff", fontSize: 16, fontWeight: "bold" },
});
