import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';

class AppTheme {
  // Primary Color from User Request: #13EC25
  // Primary Color updated to match Success/submit button (Darker Green)
  static const Color primaryColor = Color(0xFF198754);
  static const Color primaryDarkColor = Color(0xFF157347);
  
  static const Color secondaryColor = Color(0xFF0D6EFD); // Blue accent
  static const Color errorColor = Color(0xFFDC3545);
  static const Color successColor = Color(0xFF198754);
  
  static const Color scaffoldBackgroundColor = Color(0xFFF6F8F6); // Reference: #f6f8f6
  static const Color darkBackgroundColor = Color(0xFF102212); // Reference: #102212
  static const Color surfaceLightColor = Colors.white; // Reference: #ffffff
  static const Color surfaceDarkColor = Color(0xFF1A2E1D); // Reference: #1a2e1d
  
  static const Color textMainColor = Color(0xFF0D1B0F); // Reference: #0d1b0f
  static const Color textSubColor = Color(0xFF4C9A52); // Reference: #4c9a52
  
  static ThemeData get lightTheme {
    return ThemeData(
      useMaterial3: true,
      colorScheme: ColorScheme.fromSeed(
        seedColor: primaryColor,
        primary: primaryColor,
        secondary: secondaryColor,
        error: errorColor,
        background: scaffoldBackgroundColor,
      ),
      scaffoldBackgroundColor: scaffoldBackgroundColor,
      textTheme: GoogleFonts.lexendTextTheme(),
      appBarTheme: const AppBarTheme(
        backgroundColor: Colors.white,
        foregroundColor: Colors.black87,
        elevation: 0,
        centerTitle: true,
      ),
      elevatedButtonTheme: ElevatedButtonThemeData(
        style: ElevatedButton.styleFrom(
          backgroundColor: primaryColor,
          foregroundColor: Colors.white,
          elevation: 0,
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(12),
          ),
          padding: const EdgeInsets.symmetric(vertical: 16, horizontal: 24),
          textStyle: GoogleFonts.lexend(
            fontWeight: FontWeight.w600,
            fontSize: 16,
          ),
        ),
      ),
      inputDecorationTheme: InputDecorationTheme(
        filled: true,
        fillColor: Colors.white,
        border: OutlineInputBorder(
          borderRadius: BorderRadius.circular(12),
          borderSide: const BorderSide(color: Colors.grey),
        ),
        enabledBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(12),
          borderSide: BorderSide(color: Colors.grey.shade300),
        ),
        focusedBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(12),
          borderSide: const BorderSide(color: primaryColor, width: 2),
        ),
        contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 16),
      ),
    );
  }
}
