import java.util.*;

/**
 * 四子连游戏，弱化版的五子棋。
 */
public class FourDots{
    public static void main(String[] args) {
        char[][] board = initialBoard();
        printBoard(board);
        char winner = ' ';
        boolean isFull = false;
        boolean redTurn = true;
        Scanner input = new Scanner(System.in);
        int col;
        while (winner == ' ' && !isFull) {
            System.out.print("Drop a " + (redTurn ? "red" : "yellow") + 
            " disk at column (0-6): ");
            col = input.nextInt();
            for (int i = board.length - 1; i >= 0; i--) {
                if (board[i][col] == ' ') {
                    board[i][col] = redTurn ? 'R' : 'Y';
                    break;
                }
            }
            printBoard(board);
            winner = checkWinner(board);
            if (winner == 'R') {
                System.out.println("The red player won");
                break;
            }
            else if (winner == 'Y') {
                System.out.println("The yellow player won");
                break;
            }
            isFull = checkFull(board);
            redTurn = !redTurn;
        }
        if(winner == ' ')
            System.out.println("Tie, no player won");
    }

    public static char[][] initialBoard() {
        char[][] board = new char[6][7];
        for (int i = 0; i < board.length; i++) {
            for (int j = 0; j < board[i].length; j++) {
                board[i][j] = ' ';
            }
        }
        return board;
    }

    public static void printBoard(char[][] board) {
        for (int i = 0; i < board.length; i++) {
            for (int j = 0; j < board[i].length; j++) {
                System.out.print("|" + board[i][j]);
                if ((j + 1) % board[i].length == 0) {
                    System.out.print("|\n");
                }
            }
        }
        System.out.println("_______________");
    }

    public static char checkWinner(char[][] board) {
        char winner = ' ';
        for (int i = 0; i < board.length; i++) {
            if ((winner = checkFourDots(board, i, 0, 0, 1)) != ' ') {
                return winner;
            }
        }
        for (int j = 0; j < board[0].length; j++) {
            if ((winner = checkFourDots(board, 0, j, 1, 0)) != ' ') {
                return winner;
            }
        }
        for (int i = 0; i < board.length; i++) {
            if ((winner = checkFourDots(board, i, 0, 1, 1)) != ' ') {
                return winner;
            }
        }
        for (int j = 0; j < board[0].length; j++) {
            if ((winner = checkFourDots(board, 0, j, 1, 1)) != ' ') {
                return winner;
            }
        }
        for (int i = 0; i < board.length; i++) {
            if ((winner = checkFourDots(board, i, board[i].length - 1, 1, -1)) != ' ') {
                return winner;
            }
        }
        for (int j = 0; j < board[0].length; j++) {
            if ((winner = checkFourDots(board, 0, j, 1, -1)) != ' ') {
                return winner;
            }
        }
        return winner;
    }

    /**
     * 这里检查四子相等的情况其实包括了四个连续的空格，
     * 所以并不严谨。只是为了方便这样做，正确做法应该是
     * 遇到空格就跳过并重新计数。只是这里最多只有7个元素遍历，
     * 如果有4个连续的空格，就不会存在任何玩家获胜（相等于
     * ' '获胜）
     * @param board
     * @param row
     * @param col
     * @param rOff
     * @param cOff
     * @return
     */
    public static char checkFourDots(char[][] board, int row, 
    int col, int rOff, int cOff) {
        int preRow = row;
        int preCol = col;
        row += rOff;
        col += cOff;
        int dot = 1;
        char player = ' ';
        while (row >= 0 && row < board.length && 
        col >= 0 && col < board[row].length) {
            player = board[row][col];
            if (player != board[preRow][preCol]) {
                dot = 0;
            }
            preRow = row;
            preCol = col;
            row += rOff;
            col += cOff;
            dot++;
            if (dot == 4) {
                //注意这里不能直接break了然后在while外面return player
                return player;
            }
        }
        return ' ';
    }

    public static boolean checkFull(char[][] board) {
        for (int i = 0; i < board.length; i++) {
            for (int j = 0; j < board[i].length; j++) {
                if (board[i][j] == ' ') {
                    return false;
                }
            }
        }
        return true;
    }
}