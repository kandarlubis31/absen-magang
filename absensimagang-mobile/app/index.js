import axios from "axios";
import { useState } from "react";
import {
  ActivityIndicator,
  Alert,
  StatusBar,
  StyleSheet,
  Text,
  TextInput,
  TouchableOpacity,
  View,
} from "react-native";

const API_URL = "http://192.168.1.10:8000/api"; // PASTIKAN IP INI BENAR

export default function LoginPage() {
    const [nim, setNim] = useState("");
    const [password, setPassword] = useState("");
    const [isLoading, setIsLoading] = useState(false);

    const handleLogin = async () => {
        if (!nim.trim() || !password.trim()) {
            Alert.alert("Error", "NIM dan Password tidak boleh kosong.");
            return;
        }

        setIsLoading(true);

        try {
            const response = await axios.post(`${API_URL}/login`, {
                nim: nim,
                password: password,
            });

            console.log("Login Berhasil:", response.data);
            Alert.alert(
                "Sukses",
                `Login berhasil! Token kamu adalah: ${response.data.access_token}`
            );
        } catch (error) {
            console.error(
                "Login Gagal:",
                error.response ? error.response.data : error.message
            );
            Alert.alert(
                "Login Gagal",
                "NIM atau password salah. Silakan coba lagi."
            );
        } finally {
            setIsLoading(false);
        }
    };

    return (
        <View style={styles.container}>
            <StatusBar barStyle="light-content" />
            <Text style={styles.title}>Sistem Absensi</Text>
            <Text style={styles.subtitle}>Login Mahasiswa Magang</Text>

            <TextInput
                style={styles.input}
                placeholder="Masukkan NIM"
                placeholderTextColor="#888"
                value={nim}
                onChangeText={setNim}
                keyboardType="numeric"
                autoCapitalize="none"
            />

            <TextInput
                style={styles.input}
                placeholder="Masukkan Password"
                placeholderTextColor="#888"
                value={password}
                onChangeText={setPassword}
                secureTextEntry
            />

            <TouchableOpacity
                style={styles.button}
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
    );
}

const styles = StyleSheet.create({
    container: {
        flex: 1,
        backgroundColor: "#1a202c",
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
    subtitle: {
        fontSize: 16,
        color: "#a0aec0",
        marginBottom: 40,
    },
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
        justifyContent: "center",
        marginTop: 10,
    },
    buttonText: {
        color: "#fff",
        fontSize: 16,
        fontWeight: "bold",
    },
});
