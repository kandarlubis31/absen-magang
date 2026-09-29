import { StyleSheet, Text, View } from 'react-native';

export default function HistoryScreen() {
  return (
    <View style={styles.container}>
      <Text style={styles.title}>Halaman Riwayat Absensi</Text>
      <Text style={styles.subtitle}>Fitur ini akan segera dikembangkan.</Text>
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, alignItems: 'center', justifyContent: 'center', backgroundColor: '#1a202c', padding: 20 },
  title: { fontSize: 22, fontWeight: 'bold', color: '#fff' },
  subtitle: { fontSize: 16, color: '#a0aec0', marginTop: 8 },
});