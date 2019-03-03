public class Charplay{
    public static void main(String[] args){
        System.out.println("\u03b1 is a \"special sign\".");
        byte c1 = 'a';
        byte c2 = (byte) '\uFFF4';
        int c3 = 'a';
        int c4 = '\uFFF4';
        System.out.println(c1);
        System.out.println(c2);
        System.out.println(c3);
        System.out.println(c4);
        System.out.println((char)c3 + " represents for character a");
        System.out.println('b' - 'a');
        System.out.println('a' + 1.01);
    }
}