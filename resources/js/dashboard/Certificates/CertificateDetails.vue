<template>
    <div class="certificate-container">
      <h2>Votre Certificat</h2>
      <button @click="generateCertificate" class="btn btn-primary">
        Générer le Certificat
      </button>
  
      <div v-if="qrCodeUrl">
        <p>Scannez ce QR Code pour afficher votre certificat :</p>
        <img :src="certificate.qr_code" alt="QR Code" />
      </div>
  
      <div v-if="certificateLink">
        <p>Attention : L'accès direct au certificat via ce lien est interdit.</p>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from "vue";
  import axios from "axios";
  import VueQrcode from "@chenfengyuan/vue-qrcode"; 
  
  const qrCodeUrl = ref(null);
  const certificateLink = ref(null);
  
  const examUserId = "112";
  const generateCertificate = async () => {
    try {
      const response = await axios.post(`/certificates/generate/112`);
      certificateLink.value = response.data.certificate_url;
      qrCodeUrl.value = response.data.qr_code;
    } catch (error) {
      console.error("Erreur lors de la génération du certificat :", error);
    }
  };
  </script>
  
  <style scoped>
  .certificate-container {
    text-align: center;
    margin-top: 20px;
  }
  
  .btn-primary {
    background-color: #007bff;
    border: none;
    color: white;
    padding: 10px 20px;
    cursor: pointer;
  }
  
  .btn-primary:hover {
    background-color: #0056b3;
  }
  </style>