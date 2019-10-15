/*
*This is the main class
 */
package StudentManagementSystem;

/**
 * @author Radi
 */
public class Test {
    public static void main(String[] a){
        Person person=new Person("Shohag Rana", "Setor 10,Uttara Model Town", "01777873960", "rana@gmail.com");
        Student student=new Student("Rahat Khan", "Uttara Model Town", "8801777873960", "rahat@iubat.edu");
        Employee employee=new Employee("Rahat Khan", "Uttara Model Town", "8801777873960", "rahat@iubat.edu", "Bosundhara", 1000);
        Faculty faculty=new Faculty("Samim Akter", "Rampura", "+88019398652", "samim@email.com", "IUBAT campus", 1000, "8:30 am to 5.25 pm", "Professor");
        Staff staff=new Staff("Riznar Nahar", "Uttara", "+88019398652", "rizna@email.com", "IUBAT campus", 1000, "Registar");
        person.toString();
        System.out.println("This section is about stuent information \n\n");
        System.out.println(student.toString());
        System.out.println("This section is about employee information \n\n");
        System.out.println(employee.toString());
        System.out.println("This section is about faculty information \n\n");
        System.out.println(faculty.toString());
        System.out.println("This section is about staff information \n\n");
        System.out.println(staff.toString());
    }
}
