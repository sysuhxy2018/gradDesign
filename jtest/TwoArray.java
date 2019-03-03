import java.util.*;

public class TwoArray{
    public static void main(String[] args) {
        int[][] matrix = new int[5][];
        matrix[0] = new int[5];
        matrix[1] = new int[]{1, 2, 3};
        //matrix[2] = {1, 2, 3, 4};
        //matrix[2] = null;
        matrix[3] = new int[0];
        matrix[4] = null;

        printTwoArray(matrix);
        System.out.println(matrix[2] == null);

        int[][] matrix2 = {{1, 2, 3}, {3}, null, {1, 2}};
        printTwoArray(matrix2);
        int[][] matrix3 = {{1, 2}, {2, 3,}, };
        printTwoArray(matrix3);

        int[][] a = {, };

        int[][] matrix4 = new int[][]{null, {1,2,3}, {2,3}, };
        printTwoArray(matrix4);

        System.out.printf("%d %d %d %d %d\n", matrix.length, matrix2.length,
        matrix3.length, a.length, matrix4.length);
    }

    public static void printTwoArray(int[][] matrix) {
        for (int i = 0; i < matrix.length; i++) {
            if(matrix[i] != null)
                for (int j = 0; j < matrix[i].length; j++)
                    System.out.print(matrix[i][j] + " ");
            System.out.println();
        }
    }
}