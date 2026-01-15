import 'dart:io';
import 'package:google_mlkit_face_detection/google_mlkit_face_detection.dart';

class FaceDetectionService {
  late FaceDetector _faceDetector;

  FaceDetectionService() {
    final options = FaceDetectorOptions(
      enableClassification: true, // For eyes open probability
      enableLandmarks: true,
      enableTracking: false,
      performanceMode: FaceDetectorMode.accurate,
      minFaceSize: 0.15, // Detect faces that take up at least 15% of the image width
    );
    _faceDetector = FaceDetector(options: options);
  }

  Future<String?> validateFace(File imageFile) async {
    final inputImage = InputImage.fromFile(imageFile);
    
    try {
      final List<Face> faces = await _faceDetector.processImage(inputImage);

      // 1. Check if ANY face is detected
      if (faces.isEmpty) {
        return 'Wajah tidak terdeteksi. Pastikan wajah terlihat jelas.';
      }

      // 2. Check if MULTIPLE faces are detected
      if (faces.length > 1) {
        return 'Terdeteksi lebih dari satu wajah. Pastikan hanya Anda yang ada di foto.';
      }

      final Face face = faces.first;

      // 3. Check Head Rotation (User must look straight)
      // headEulerAngleY: Head is rotated to the right (positive) or left (negative).
      // headEulerAngleZ: Head is tilted sideways.
      if (face.headEulerAngleY != null) {
        if (face.headEulerAngleY! > 15 || face.headEulerAngleY! < -15) {
          return 'Wajah jangan menoleh. Harap menghadap lurus ke kamera.';
        }
      }

      // 4. Check Liveness (Eyes Open) - Only if classification enabled
      // Note: Some devices/lighting might make this unreliable, so we use a lenient threshold.
      // Probability is 0.0 to 1.0.
      if (face.leftEyeOpenProbability != null && face.rightEyeOpenProbability != null) {
        final double leftEye = face.leftEyeOpenProbability!;
        final double rightEye = face.rightEyeOpenProbability!;
        
        // Threshold 0.1 is very low (basically just "not tightly shut") to avoid false positives.
        if (leftEye < 0.1 || rightEye < 0.1) {
          return 'Mata kedapatan tertutup. Harap buka mata Anda.';
        }
      }

      // All checks passed
      return null; 
    } catch (e) {
      return 'Gagal memproses validasi wajah: $e';
    }
  }

  void dispose() {
    _faceDetector.close();
  }
}
